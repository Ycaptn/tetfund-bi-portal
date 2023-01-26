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


class TFBiBeneficiaryRequestMigration extends Command
{
    protected $signature = 'tetfund:bi-beneficiary-request-migration';
    protected $description = 'Migrates beneficiary_request from TETFund_Iterum_Portal database to TETFund_BI-Portal submissions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $host = request()->getHost();
        $manager = new OrganizationManager();
        $organization = $manager->loadTenant($host);
        if ($organization == null){
            echo "Default Organization NOT Found \n";    
            return 0;
        }

        $bi_db_config_name = "mysql";
        $iterum_db_config_name = "mysql2";
        echo "Running Beneficiary Request Migration on TETFund_Iterum_Portal to Submission Request on TETFund_Beneficiary_Portal \n";

        $iterum_beneficiary_requests = DB::connection($iterum_db_config_name)
                                        ->table("tf_bip_beneficiary_requests")
                                        ->orderBy('parent_id','ASC')
                                        ->get();

        $beneficiary_requests_count = count($iterum_beneficiary_requests);
        $successful_replicated_beneficiary_request_count = 0;
        $successful_replicated_beneficiary_request_count_created = 0;
        $successful_replicated_beneficiary_request_count_updated = 0;

        echo ">>> Found {$beneficiary_requests_count} Beneficiary Request Records for TETFund-BI-Portal Replication \n \n \n";

        foreach($iterum_beneficiary_requests as $idx=>$iterum_beneficiary_request){

            DB::connection($bi_db_config_name)->beginTransaction();

            try {
                
                $bi_portal_beneficiary = DB::connection($bi_db_config_name)
                       ->table("tf_bi_portal_beneficiaries")
                       ->where('tf_iterum_portal_key_id', $iterum_beneficiary_request->beneficiary_id)
                       ->whereNull('deleted_at')
                       ->first();
                
                if ($bi_portal_beneficiary != null) {

                        // generating desk officer email from beneficiary short-name
                        $desk_officer_email = strtolower($this->sanitize_email_prefix($bi_portal_beneficiary->short_name) . "@tetfund.gov.ng");

                        // fetching desk officer user details
                        $desk_officer = User::where('email', $desk_officer_email)->first();

                        // fetching submission request fro BI-portal having similar details
                        $submission_request = SubmissionRequest::where([
                                'tf_iterum_portal_key_id' => $iterum_beneficiary_request->id,
                                'beneficiary_id' => $bi_portal_beneficiary->id,
                                'tf_iterum_intervention_line_key_id' => $iterum_beneficiary_request->interven_benef_type_id,
                                'intervention_year1' => $iterum_beneficiary_request->intervention_year1 ?? '0',
                                'intervention_year2' => $iterum_beneficiary_request->intervention_year2 ?? '0',
                                'intervention_year3' => $iterum_beneficiary_request->intervention_year3 ?? '0',
                                'intervention_year4' => $iterum_beneficiary_request->intervention_year4 ?? '0',
                            ])->first();
                        // checking if submission request exist or not
                        if (empty($submission_request) || $submission_request == null) {
                            $submission_request = new SubmissionRequest();
                            echo ">>> No. ". strval(intval($idx)+1) ." Beneficiary Submission Request Record Created - {$iterum_beneficiary_request->title} \n";
                            $successful_replicated_beneficiary_request_count_created++;
                        } else {
                            echo ">>> No. ". strval(intval($idx)+1) ." Beneficiary Request Submission Record Updated - {$iterum_beneficiary_request->title}\n";
                            $successful_replicated_beneficiary_request_count_updated++;
                        }

                        // creating/updating submission request data
                        $submission_request->organization_id = $organization->id;
                        $submission_request->title = $iterum_beneficiary_request->title;
                        
                        $submission_request->status = ($iterum_beneficiary_request->request_sent_date != null && $iterum_beneficiary_request->request_received_date != null) ? 'submitted' : 'not-submitted';
                        
                        $submission_request->type = 'intervention';
                        $submission_request->requesting_user_id = $desk_officer->id;
                        $submission_request->beneficiary_id = $bi_portal_beneficiary->id;
                        $submission_request->display_ordinal = $iterum_beneficiary_request->display_ordinal;
                        
                        $submission_request->intervention_year1 = $iterum_beneficiary_request->intervention_year1 ?? 0;
                        $submission_request->intervention_year2 = $iterum_beneficiary_request->intervention_year2 ?? 0;
                        $submission_request->intervention_year3 = $iterum_beneficiary_request->intervention_year3 ?? 0;
                        $submission_request->intervention_year4 = $iterum_beneficiary_request->intervention_year4 ?? 0;

                        $submission_request->proposed_request_date = $iterum_beneficiary_request->request_sent_date;

                        $submission_request->tf_iterum_portal_key_id = $iterum_beneficiary_request->id ?? null;
                        $submission_request->tf_iterum_portal_request_status = $iterum_beneficiary_request->request_status ?? null;
                        $submission_request->tf_iterum_portal_response_meta_data = $iterum_beneficiary_request ? json_encode($iterum_beneficiary_request) : null;
                        $submission_request->tf_iterum_portal_response_at = $iterum_beneficiary_request->request_received_date ?? null;

                        $submission_request->created_at = $iterum_beneficiary_request->created_at;
                        $submission_request->updated_at = $iterum_beneficiary_request->updated_at;
                        $submission_request->deleted_at = $iterum_beneficiary_request->deleted_at;

                        $submission_request->amount_requested = $iterum_beneficiary_request->request_amount;
                        $submission_request->tf_iterum_intervention_line_key_id = $iterum_beneficiary_request->interven_benef_type_id;

                        $submission_request->save();
                        $successful_replicated_beneficiary_request_count++; // incrementing request counter

                        // obtaining attachable for request
                        echo ">>>>> Fetching Iterum-Portal Beneficiary Request Attachment Records \n";
                        $iterum_beneficiary_requests_attachable = DB::connection($iterum_db_config_name)
                                        ->table("fc_attachables")
                                        ->where('attachable_id', $iterum_beneficiary_request->id)
                                        ->whereNull('deleted_at')
                                        ->get();                        

                        // processing attachments beloging to beneficiary request
                        if (isset($iterum_beneficiary_requests_attachable) && count($iterum_beneficiary_requests_attachable) > 0) {

                            $successful_replicated_beneficiary_request_attachment_count = count($iterum_beneficiary_requests_attachable);
                            $successful_replicated_beneficiary_request_attachment_count_created = 0;
                            $successful_replicated_beneficiary_request_attachment_count_updated = 0;

                            echo ">>>>> Found ". $successful_replicated_beneficiary_request_attachment_count ." Iterum-Portal Beneficiary Request Attachment Record(s) \n";

                            foreach($iterum_beneficiary_requests_attachable as $idx => $attachable_rec) {
                                
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
                                    echo ">>>>> No. ". strval(intval($idx)+1) ." Beneficiary Request Attachment Record Created - {$iterum_attachment_rec->label}\n";
                                    $successful_replicated_beneficiary_request_attachment_count_created++;

                                    // saving new attachment record
                                    $attachOBJ = new Attachment();
                                    $attachOBJ->path = $iterum_attachment_rec->path;
                                    $attachOBJ->label = $iterum_attachment_rec->label;
                                    $attachOBJ->organization_id = $organization->id;
                                    $attachOBJ->uploader_user_id = $desk_officer->id;
                                    $attachOBJ->description = $iterum_attachment_rec->description;
                                    $attachOBJ->file_type = $iterum_attachment_rec->file_type;
                                    $attachOBJ->storage_driver = $iterum_attachment_rec->storage_driver;
                                    $attachOBJ->save();

                                    // saving new attachable record
                                    $attachableOBJ = new EloquentAttachable();
                                    $attachableOBJ->user_id = $desk_officer->id;
                                    $attachableOBJ->attachment_id = $attachOBJ->id;
                                    $attachableOBJ->attachable_id = $submission_request->id;
                                    $attachableOBJ->attachable_type = get_class($submission_request);
                                    $attachableOBJ->save();

                                } else {
                                    echo ">>>>> No. ". strval(intval($idx)+1) ." Beneficiary Request Attachment Record Updated - {$iterum_attachment_rec->label}\n";
                                    $successful_replicated_beneficiary_request_attachment_count_updated++;

                                    // updating attachment record;
                                    $bi_attachment_rec->path = $iterum_attachment_rec->path;
                                    $bi_attachment_rec->label = $iterum_attachment_rec->label;
                                    $bi_attachment_rec->organization_id = $organization->id;
                                    $bi_attachment_rec->uploader_user_id = $desk_officer->id;
                                    $bi_attachment_rec->description = $iterum_attachment_rec->description;
                                    $bi_attachment_rec->file_type = $iterum_attachment_rec->file_type;
                                    $bi_attachment_rec->storage_driver = $iterum_attachment_rec->storage_driver;
                                    $bi_attachment_rec->save();

                                    // saving new attachable record
                                    $attachableOBJ = EloquentAttachable::where('attachment_id', $bi_attachment_rec->id)
                                            ->where('attachable_id', $submission_request->id)
                                            ->first();
                                    $attachableOBJ->user_id = $desk_officer->id;
                                    $attachableOBJ->attachment_id = $bi_attachment_rec->id;
                                    $attachableOBJ->attachable_id = $submission_request->id;
                                    $attachableOBJ->attachable_type = get_class($submission_request);
                                    $attachableOBJ->save();
                                }

                            }

                            echo ">>>> {$successful_replicated_beneficiary_request_attachment_count} Attachment record(s) belonging to this beneficiary requests detected \n";
                            echo ">>>> {$successful_replicated_beneficiary_request_attachment_count_created} Iterum-Portal beneficiary request attachment record(s) created \n";
                            echo ">>>> {$successful_replicated_beneficiary_request_attachment_count_updated} Iterum-Portal beneficiary request attachment record(s) updated \n \n \n";

                        } else {
                            echo ">>>>> No Attachment Record Found For This Iterum-Portal Beneficiary Request \n \n \n";
                        }
        


                } else {
                    echo "Skipping - Beneficiary Request Record - ERROR - Count not replicate Beneficiary Request \n";
                    if ($bi_portal_beneficiary == null) {
                        echo ">>> Beneficiary Record is Missing \n \n \n";
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

        echo "{$beneficiary_requests_count} Iterum-Portal Beneficiary requests detected \n";
        echo "{$successful_replicated_beneficiary_request_count} successfully processed \n";
        echo "{$successful_replicated_beneficiary_request_count_created} Iterum-Portal beneficiary request records created \n";
        echo "{$successful_replicated_beneficiary_request_count_updated} Iterum-Portal beneficiary request records updated \n";
        echo "Done Beneficiary Request Migration \n";
        
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
