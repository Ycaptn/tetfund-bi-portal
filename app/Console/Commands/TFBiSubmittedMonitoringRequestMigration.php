<?php

namespace App\Console\Commands;

use Session;
use Validator;
use Carbon\Carbon;

use App\Models\SubmissionRequest;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

use Hasob\FoundationCore\Models\User;
use Hasob\FoundationCore\Models\Attachment;
use Hasob\FoundationCore\Models\Organization;
use Hasob\FoundationCore\Managers\OrganizationManager;
use Hasob\FoundationCore\Models\Attachable as EloquentAttachable;


class TFBiSubmittedMonitoringRequestMigration extends Command
{
    protected $signature = 'tetfund:bi-submitted-monitoring-requests-migration';    
    protected $description = 'Migrates submitted monitoring requests from TETFund_Iterum_Portal database to TETFund_BI-Portal submissions';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        $host = request()->getHost();
        $manager = new OrganizationManager();
        $organization = $manager->loadTenant($host);
        if ($organization == null){
            echo "Default Organization NOT Found \n";    
            return 0;
        }

        $bi_db_config_name = "mysql";
        $iterum_db_config_name = "mysql2";

        echo "Running Submitted Monitoring Requests Migration from TETFund_Iterum_Portal to Submission Request on TETFund_Beneficiary_Portal \n";

        $iterum_monitoring_requests = DB::connection($iterum_db_config_name)
                                        ->table("tf_me_monitoring_requests")
                                        ->orderBy('created_at','ASC')
                                        ->where('status', '!=', 'new')
                                        ->whereNull('deleted_at')
                                        ->get();

        $monitoring_requests_count = count($iterum_monitoring_requests);
        $successful_replicated_monitoring_request_count = 0;
        $successful_replicated_monitoring_request_count_created = 0;
        $successful_replicated_monitoring_request_count_updated = 0;

        echo ">>> Found {$monitoring_requests_count} Submitted Monitoring Request Records for TETFund-BI-Portal Replication \n \n \n";

        foreach($iterum_monitoring_requests as $idx=>$iterum_monitoring_request){

            DB::connection($bi_db_config_name)->beginTransaction();

            try {

                $bi_portal_beneficiary = DB::connection($bi_db_config_name)
                       ->table("tf_bi_portal_beneficiaries")
                       ->where('tf_iterum_portal_key_id', $iterum_monitoring_request->beneficiary_id)
                       ->whereNull('deleted_at')
                       ->first();

                if ($bi_portal_beneficiary != null) {

                        // generating desk officer email from beneficiary short-name
                        $desk_officer_email = strtolower($this->sanitize_email_prefix($bi_portal_beneficiary->short_name) . "@tetfund.gov.ng");

                        // fetching desk officer user details
                        $desk_officer = User::where('email', $desk_officer_email)->first();

                        // fetching AIP submission request from BI-portal having similar details
                        $submission_request = SubmissionRequest::whereNull('parent_id')
                            ->where([
                                'is_aip_request' => true,
                                'is_monitoring_request' => false,
                                'beneficiary_id' => $bi_portal_beneficiary->id,
                                'tf_iterum_portal_key_id' => $iterum_monitoring_request->beneficiary_request_id,
                            ])->first();

                        // skip if submission_request aip does not exist
                        if (empty($submission_request) || $submission_request == null) {
                            continue;
                        }

                        // fetching monitoring request from BI-portal having similar details
                        $bi_monitoring_request = SubmissionRequest::where([
                                'is_monitoring_request' => true,
                                'parent_id' => $submission_request->id,
                                'tf_iterum_portal_key_id' => $iterum_monitoring_request->id,
                                'beneficiary_id' => $bi_portal_beneficiary->id,
                                'intervention_year1' => $submission_request->intervention_year1 ?? '0',
                                'intervention_year2' => $submission_request->intervention_year2 ?? '0',
                                'intervention_year3' => $submission_request->intervention_year3 ?? '0',
                                'intervention_year4' => $submission_request->intervention_year4 ?? '0',
                            ])->first();

                        // checking if monitoring request exist or not
                        if (empty($bi_monitoring_request) || $bi_monitoring_request == null) {
                            $bi_monitoring_request = new SubmissionRequest();
                            echo ">>> No. ". strval(intval($idx)+1) ." Submitted Monitoring Request Record Created - {$iterum_monitoring_request->title} \n";
                            $successful_replicated_monitoring_request_count_created++;
                        } else {
                            echo ">>> No. ". strval(intval($idx)+1) ." Submitted Monitoring Request Record Updated - {$iterum_monitoring_request->title}\n";
                            $successful_replicated_monitoring_request_count_updated++;
                        }

                        // creating/updating monitoring request data
                        $bi_monitoring_request->organization_id = $organization->id;
                        $bi_monitoring_request->title = $iterum_monitoring_request->title;                        
                        $bi_monitoring_request->status = 'submitted';                        
                        $bi_monitoring_request->type = 'monitoring';
                        $bi_monitoring_request->requesting_user_id = $desk_officer->id;
                        $bi_monitoring_request->beneficiary_id = $bi_portal_beneficiary->id;
                        $bi_monitoring_request->display_ordinal = $iterum_monitoring_request->display_ordinal;                      
                        $bi_monitoring_request->intervention_year1 = $submission_request->intervention_year1 ?? 0;
                        $bi_monitoring_request->intervention_year2 = $submission_request->intervention_year2 ?? 0;
                        $bi_monitoring_request->intervention_year3 = $submission_request->intervention_year3 ?? 0;
                        $bi_monitoring_request->intervention_year4 = $submission_request->intervention_year4 ?? 0;
                        $bi_monitoring_request->proposed_request_date = $iterum_monitoring_request->proposed_request_date;
                        $bi_monitoring_request->tf_iterum_portal_key_id = $iterum_monitoring_request->id ?? null;
                        $bi_monitoring_request->tf_iterum_portal_request_status = $iterum_monitoring_request->status ?? null;
                        $bi_monitoring_request->tf_iterum_portal_response_meta_data = $iterum_monitoring_request ? json_encode($iterum_monitoring_request) : null;
                        $bi_monitoring_request->tf_iterum_portal_response_at = $iterum_monitoring_request->final_request_date ?? null;
                        $bi_monitoring_request->created_at = $iterum_monitoring_request->created_at;
                        $bi_monitoring_request->updated_at = $iterum_monitoring_request->updated_at;
                        $bi_monitoring_request->amount_requested = $submission_request->amount_requested;
                        $bi_monitoring_request->tf_iterum_intervention_line_key_id = $submission_request->interven_benef_type_id;
                        $bi_monitoring_request->parent_id = $submission_request->id;
                        $bi_monitoring_request->is_monitoring_request = true;

                        $bi_monitoring_request->save();    // saving request
                        $successful_replicated_monitoring_request_count++; // incrementing request counter

                        // obtaining attachable for request
                        echo ">>>>> Fetching Iterum-Portal Submitted Monitoring Request Attachment Records \n";
                        $iterum_monitoring_requests_attachable = DB::connection($iterum_db_config_name)
                                        ->table("fc_attachables")
                                        ->where('attachable_id', $iterum_monitoring_request->id)
                                        ->whereNull('deleted_at')
                                        ->get();                        

                        // processing attachments beloging to monitoring request
                        if (isset($iterum_monitoring_requests_attachable) && count($iterum_monitoring_requests_attachable) > 0) {

                            $successful_replicated_monitoring_request_attachment_count = count($iterum_monitoring_requests_attachable);
                            $successful_replicated_monitoring_request_attachment_count_created = 0;
                            $successful_replicated_monitoring_request_attachment_count_updated = 0;
                            echo ">>>>> Found ". $successful_replicated_monitoring_request_attachment_count ." Iterum-Portal Submitted Monitoring Request Attachment Record(s) \n";

                            foreach($iterum_monitoring_requests_attachable as $idx => $attachable_rec) {
                                
                                // iterum portal attachment record
                                $iterum_attachment_rec = DB::connection($iterum_db_config_name) 
                                            ->table("fc_attachments")
                                            ->where('id', $attachable_rec->attachment_id)
                                            ->whereNull('deleted_at')
                                            ->first();

                                // bi portal attachment record
                                $bi_attachment_rec = $submission_request->get_specific_attachment($submission_request->id, $iterum_attachment_rec->label);
                                         
                                // checking if submission request exist or not
                                if (empty($bi_attachment_rec) || $bi_attachment_rec == null) {
                                    echo ">>>>> No. ". strval(intval($idx)+1) ." Submitted Monitoring Request Attachment Record Created - {$iterum_attachment_rec->label}\n";
                                    $successful_replicated_monitoring_request_attachment_count_created++;

                                    // saving new attachment record
                                    $attachOBJ = new Attachment();
                                    $attachOBJ->path = $iterum_attachment_rec->path;
                                    $attachOBJ->label = $iterum_attachment_rec->label;
                                    $attachOBJ->organization_id = $organization->id;
                                    $attachOBJ->uploader_user_id = $desk_officer->id;
                                    $attachOBJ->description = $iterum_attachment_rec->description;
                                    $attachOBJ->file_type = $iterum_attachment_rec->file_type;
                                    $attachOBJ->storage_driver = $iterum_attachment_rec->storage_driver;
                                    $attachOBJ->created_at = $iterum_attachment_rec->created_at;
                                    $attachOBJ->updated_at = $iterum_attachment_rec->updated_at;
                                    $attachOBJ->save();

                                    // saving new attachable record
                                    $attachableOBJ = new EloquentAttachable();
                                    $attachableOBJ->user_id = $desk_officer->id;
                                    $attachableOBJ->attachment_id = $attachOBJ->id;
                                    $attachableOBJ->attachable_id = $submission_request->id;
                                    $attachableOBJ->attachable_type = get_class($submission_request);
                                    $attachableOBJ->created_at = $iterum_attachment_rec->created_at;
                                    $attachableOBJ->updated_at = $iterum_attachment_rec->updated_at;
                                    $attachableOBJ->save();

                                } else {
                                    echo ">>>>> No. ". strval(intval($idx)+1) ." Submitted Monitoring Request Attachment Record Updated - {$iterum_attachment_rec->label}\n";
                                    $successful_replicated_monitoring_request_attachment_count_updated++;

                                    // updating attachment record;
                                    $bi_attachment_rec->path = $iterum_attachment_rec->path;
                                    $bi_attachment_rec->label = $iterum_attachment_rec->label;
                                    $bi_attachment_rec->organization_id = $organization->id;
                                    $bi_attachment_rec->uploader_user_id = $desk_officer->id;
                                    $bi_attachment_rec->description = $iterum_attachment_rec->description;
                                    $bi_attachment_rec->file_type = $iterum_attachment_rec->file_type;
                                    $bi_attachment_rec->storage_driver = $iterum_attachment_rec->storage_driver;
                                    $bi_attachment_rec->created_at = $iterum_attachment_rec->created_at;
                                    $bi_attachment_rec->updated_at = $iterum_attachment_rec->updated_at;
                                    $bi_attachment_rec->save();

                                    // saving new attachable record
                                    $attachableOBJ = EloquentAttachable::where('attachment_id', $bi_attachment_rec->id)
                                            ->where('attachable_id', $submission_request->id)
                                            ->first();
                                    $attachableOBJ->user_id = $desk_officer->id;
                                    $attachableOBJ->attachment_id = $bi_attachment_rec->id;
                                    $attachableOBJ->attachable_id = $submission_request->id;
                                    $attachableOBJ->attachable_type = get_class($submission_request);
                                    $attachableOBJ->created_at = $iterum_attachment_rec->created_at;
                                    $attachableOBJ->updated_at = $iterum_attachment_rec->updated_at;
                                    $attachableOBJ->save();
                                }

                            }

                            echo ">>>> {$successful_replicated_monitoring_request_attachment_count} Attachment record(s) belonging to this submitted monitoring requests detected \n";
                            echo ">>>> {$successful_replicated_monitoring_request_attachment_count_created} Iterum-Portal submitted monitoring request attachment record(s) created \n";
                            echo ">>>> {$successful_replicated_monitoring_request_attachment_count_updated} Iterum-Portal submitted monitoring request attachment record(s) updated \n \n \n";

                        } else {
                            echo ">>>>> No Attachment Record Found For This Iterum-Portal Submitted Monitoring Request \n \n \n";
                        }
        


                } else {
                    echo "Skipping - Submitted Monitoring Request Record - ERROR - Could not replicate Monitoring Request \n";
                    if ($bi_portal_beneficiary == null) {
                        echo ">>> Monitoring Record is Missing \n \n \n";
                    }
                }

            } catch (\Exception $e) {
                DB::connection($bi_db_config_name)->rollBack();
                Log::error($e->getMessage());
                Log::error($e);
                dd($e);
            }

            DB::connection($bi_db_config_name)->commit();
        }

        echo "{$monitoring_requests_count} Iterum-Portal Submitted Monitoring requests detected \n";
        echo "{$successful_replicated_monitoring_request_count} successfully processed \n";
        echo "{$successful_replicated_monitoring_request_count_created} Iterum-Portal submitted monitoring request records created \n";
        echo "{$successful_replicated_monitoring_request_count_updated} Iterum-Portal submitted monitoring request records updated \n";
        echo "Done Submitted Monitoring Request Migration \n \n \n \n ";
        
        return 1;
    }

    /* sanitise string and remove invalid character formulating valid email prefix */
    public function sanitize_email_prefix($prefix_string) {
        $valid_prefix = trim($prefix_string);
        $valid_prefix = str_replace(' ', '-', $valid_prefix);
        $valid_prefix = str_replace('(', '', $valid_prefix);
        $valid_prefix = str_replace(')', '', $valid_prefix);
        $valid_prefix = str_replace("'", '', $valid_prefix);
        $valid_prefix = str_replace('`', '', $valid_prefix);
        $valid_prefix = str_replace('"', '', $valid_prefix);

        return $valid_prefix;
    }
}
