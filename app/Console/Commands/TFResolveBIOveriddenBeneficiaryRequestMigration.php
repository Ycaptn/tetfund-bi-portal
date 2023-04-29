<?php

namespace App\Console\Commands;

use Carbon\Carbon;

use App\Models\Beneficiary;
use App\Models\SubmissionRequest;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

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

class TFResolveBIOveriddenBeneficiaryRequestMigration extends Command
{
    
    protected $signature = 'tetfund:resolve-bi-overidden-beneficiary-request-migration';
    protected $description = 'Migrates over-ridden beneficiary from TETFund_Iterum_Portal database to TETFund BI-Portal';

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
        echo "\n \n>>> Running migration for Overriden Beneficiary Requests from TETFund_Iterum_Portal to TETFund_Beneficiary_Portal \n";

        // provide the IDs of the updated_request from the .txt file
        $updated_request = ["2516ffeb-6a21-4674-b440-1a8664d5bb09","e94d5286-2a23-4965-9e78-2020bf4ad89c","d27a7d95-2f81-4b64-adaa-76eb14589e43","3efc33a6-1bf5-480b-9adf-9caed1585422","3efc33a6-1bf5-480b-9adf-9caed1585422","61be1657-8a6f-486f-b6d8-ca0344d230cf","61be1657-8a6f-486f-b6d8-ca0344d230cf","b59b5706-699e-438b-8258-e532b2973c6c","85d65df6-9f66-4087-9728-8eb0121a48a4","85d65df6-9f66-4087-9728-8eb0121a48a4","85d65df6-9f66-4087-9728-8eb0121a48a4","dc95c53f-51cc-4a41-b411-193b2d3457d5","90659262-b916-462c-900b-5408e354e7b8","90659262-b916-462c-900b-5408e354e7b8","90d90f14-d95f-4630-9051-bf438967cfad","9d61d2a2-c335-4eac-a340-b12859414773","9d61d2a2-c335-4eac-a340-b12859414773","21c90c8b-1c26-4c54-9180-b0eb71d2edae","1d0b638d-8c09-41a4-8b3a-e06e8c8ab877","cb242202-feb3-4222-9191-63bd87a1c09a","cb242202-feb3-4222-9191-63bd87a1c09a","ba98984b-6d3e-4c33-8321-673db47a4333","eec6a2fa-d77d-436d-a61f-667238af41ad","eec6a2fa-d77d-436d-a61f-667238af41ad"];

        // provide the IDs of existing_affected_content from the .txt file
        $existing_affected_content = ["2f53f9a7-004c-4ad5-9736-837815fd87bb","31a9f860-b833-4783-94b7-f173e32b7254","333429cf-d722-4084-8115-816dcacfd8f8","6b5341b5-d11a-4dfa-a5cb-c003faf97f14","8c2e9c73-18b8-471b-b075-5e39739b8c0e","90d90f14-d95f-4630-9051-bf438967cfad","ae988719-df2d-4c4c-bd7e-5bf8e89ce47a","afdbecf7-9e2e-44aa-9130-6eb68d0074ec","d5b9b774-8ba0-4043-8c3d-a1c357cf2240"];

        $total_request_arr = array_unique(array_merge($updated_request, $existing_affected_content));
        
        $iterum_beneficiary_requests = DB::connection($iterum_db_config_name)
                    ->table("tf_bip_beneficiary_requests")
                    ->join('tf_bm_interven_benef_types', 'tf_bm_interven_benef_types.id', 'tf_bip_beneficiary_requests.interven_benef_type_id')
                    ->orderBy('tf_bip_beneficiary_requests.created_at','ASC')
                    ->where('tf_bip_beneficiary_requests.requested_tranche', 'not like', '%- Audit%')
                    ->whereNull('tf_bip_beneficiary_requests.deleted_at')
                    ->whereIn('tf_bip_beneficiary_requests.id', $total_request_arr)
                    ->get(['tf_bip_beneficiary_requests.*', 'tf_bm_interven_benef_types.intervention_id', 'tf_bm_interven_benef_types.name']);

        // items counts
        $count_total_request_arr = count($total_request_arr);
        $successful_replicated_beneficiary_request_count_created = 0;
        $successful_replicated_beneficiary_request_count_updated = 0;
        $count_iterum_beneficiary_requests = count($iterum_beneficiary_requests);

        echo "\n >>> Found a total of {$count_iterum_beneficiary_requests} Beneficiary Requests on TETFund Iterum Portal out of {$count_total_request_arr} Unique IDs array provided \n";

        if(count($iterum_beneficiary_requests) > 0) {
            foreach ($iterum_beneficiary_requests as $iterum_bi_request) {
                
                DB::connection($bi_db_config_name)->beginTransaction();

                try {
                    echo "\n >>>> Searching for Beneficiary Request with ID ". $iterum_bi_request->id ." on Beneficiary Submission Portal \n";

                    // fetching beneficiary request on BI-Portal
                    $submission_request = SubmissionRequest::where('is_monitoring_request', false)
                        ->where('tf_iterum_portal_key_id', $iterum_bi_request->id)
                        ->first();

                    // obtaining beneficiary record from BI-Portal
                    $bi_portal_beneficiary = Beneficiary::whereNull('deleted_at')
                       ->where('tf_iterum_portal_key_id', $iterum_bi_request->beneficiary_id)
                       ->first();

                    if (!empty($bi_portal_beneficiary)) {
                        // generating desk officer email from beneficiary short-name
                        $desk_officer_email = strtolower($this->sanitize_email_prefix($bi_portal_beneficiary->short_name) . "@tetfund.gov.ng");

                        // fetching desk officer user details
                        $desk_officer = User::withTrashed()->where('email', $desk_officer_email)->first();

                        if (!empty($desk_officer)) {
                            // create new request obj
                            if(empty($submission_request)) {
                                $submission_request = new SubmissionRequest();
                                $successful_replicated_beneficiary_request_count_created++;
                            } else {
                                $successful_replicated_beneficiary_request_count_updated++;
                            }

                            // creating/updating submission request data
                            $submission_request->organization_id = $organization->id;
                            $submission_request->title = $iterum_bi_request->title;                        
                            $submission_request->status = 'submitted';                        
                            $submission_request->type = $iterum_bi_request->requested_tranche;
                            $submission_request->requesting_user_id = $desk_officer->id;
                            $submission_request->beneficiary_id = $bi_portal_beneficiary->id;
                            $submission_request->display_ordinal = $iterum_bi_request->display_ordinal;
                            
                            $submission_request->intervention_year1 = $iterum_bi_request->intervention_year1 ?? 0;
                            $submission_request->intervention_year2 = $iterum_bi_request->intervention_year2 ?? 0;
                            $submission_request->intervention_year3 = $iterum_bi_request->intervention_year3 ?? 0;
                            $submission_request->intervention_year4 = $iterum_bi_request->intervention_year4 ?? 0;

                            $submission_request->proposed_request_date = $iterum_bi_request->request_sent_date;

                            $submission_request->tf_iterum_portal_key_id = $iterum_bi_request->id ?? null;
                            $submission_request->tf_iterum_portal_request_status = $iterum_bi_request->request_status ?? null;
                            $submission_request->tf_iterum_portal_response_meta_data = $iterum_bi_request ? json_encode($iterum_bi_request) : null;
                            $submission_request->tf_iterum_portal_response_at = $iterum_bi_request->request_received_date ?? null;

                            $submission_request->created_at = $iterum_bi_request->created_at;
                            $submission_request->updated_at = $iterum_bi_request->updated_at;

                            $submission_request->amount_requested = $iterum_bi_request->request_amount;
                            $submission_request->tf_iterum_intervention_line_key_id = $iterum_bi_request->intervention_id;

                            // retrieving request when iterum beneficiary request parent id is not null
                            if ($iterum_bi_request->parent_id != null) {
                                $parent_submission_request = SubmissionRequest::where([
                                    'tf_iterum_portal_key_id' => $iterum_bi_request->parent_id,
                                    'is_aip_request' => true,
                                    'tf_iterum_intervention_line_key_id' => $iterum_bi_request->intervention_id,
                                ])->first(); 

                                $submission_request->parent_id = $parent_submission_request->id ?? null;
                            }

                            $submission_request->is_aip_request = $iterum_bi_request->is_aip_request;
                            $submission_request->is_first_tranche_request = $iterum_bi_request->is_first_tranche_request;
                            $submission_request->is_second_tranche_request = $iterum_bi_request->is_second_tranche_request;
                            $submission_request->is_third_tranche_request = $iterum_bi_request->is_third_tranche_request;
                            $submission_request->is_final_tranche_request = $iterum_bi_request->is_final_tranche_request;

                            $submission_request->save();    // saving request


                            // obtaining attachable for request
                            echo ">>>>> Fetching Iterum-Portal Submitted Beneficiary Request Attachment Records \n";
                            $iterum_beneficiary_requests_attachable = DB::connection($iterum_db_config_name)
                                            ->table("fc_attachables")
                                            ->where('attachable_id', $iterum_bi_request->id)
                                            ->whereNull('deleted_at')
                                            ->get();                 

                            // processing attachments beloging to beneficiary request
                            if (isset($iterum_beneficiary_requests_attachable) && count($iterum_beneficiary_requests_attachable) > 0) {

                                $successful_replicated_beneficiary_request_attachment_count = count($iterum_beneficiary_requests_attachable);
                                $successful_replicated_beneficiary_request_attachment_count_created = 0;
                                $successful_replicated_beneficiary_request_attachment_count_updated = 0;

                                echo ">>>>> Found ". $successful_replicated_beneficiary_request_attachment_count ." Iterum-Portal Submitted Beneficiary Request Attachment Record(s) \n";

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
                                        echo ">>>>> No. ". strval(intval($idx)+1) ." Submitted Beneficiary Request Attachment Record Created - {$iterum_attachment_rec->label}\n";
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
                                        echo ">>>>> No. ". strval(intval($idx)+1) ." Submitted Beneficiary Request Attachment Record Updated - {$iterum_attachment_rec->label}\n";
                                        $successful_replicated_beneficiary_request_attachment_count_updated++;

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

                                echo ">>>> {$successful_replicated_beneficiary_request_attachment_count} Attachment record(s) belonging to this submitted beneficiary requests detected \n";
                                echo ">>>> {$successful_replicated_beneficiary_request_attachment_count_created} Iterum-Portal submitted beneficiary request attachment record(s) created \n";
                                echo ">>>> {$successful_replicated_beneficiary_request_attachment_count_updated} Iterum-Portal submitted beneficiary request attachment record(s) updated \n \n \n";

                            } else {
                                echo ">>>>> No Attachment Record Found For This Iterum-Portal Submitted Beneficiary Request \n \n \n";
                            }

                        } else {
                            echo " \n \n >>>>> Desk Officer record on BI-Portal was not found for the Iterum-Portal Beneficiary request with ID {$count_total_request_arr} \n";
                        }
        
                    } else {
                            echo " \n \n >>>>> Beneficiary Record on BI-Portal was not found for the Iterum-Portal Beneficiary request with ID {$count_total_request_arr} \n";
                    }



                } catch (\Exception $e) {
                    DB::connection($bi_db_config_name)->rollBack();
                    Log::error($e->getMessage());
                    Log::error($e);
                    dd($e);
                }

                DB::connection($bi_db_config_name)->commit();
            }
        } 



        echo "A Total of {$count_iterum_beneficiary_requests} Iterum-Portal Beneficiary request found out of {$count_total_request_arr} Unique IDs provided \n";
        echo "{$successful_replicated_beneficiary_request_count_created} Iterum-Portal submitted beneficiary request records created \n";
        echo "{$successful_replicated_beneficiary_request_count_updated} Iterum-Portal submitted beneficiary request records updated \n";
        echo "Done Resolving Overriden Beneficiary Request Migration \n \n \n \n ";
        
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
