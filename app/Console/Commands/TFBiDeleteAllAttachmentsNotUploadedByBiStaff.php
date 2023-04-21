<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\SubmissionRequest;

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

class TFBiDeleteAllAttachmentsNotUploadedByBiStaff extends Command
{
    
    protected $signature = 'tetfund:bi-delete-all-attachments-not-uploaded-by-bi-staff';
    protected $description = 'Deletes all migrated attachments not uploaded by beneficiary staffs on TETFund_BI_Portal';

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
        $old_tetfund_portal_db_config_name = "mysql3";

        echo "\nRunning migration to delete all beneficiary request attachments not uploaded by beneficiary staffs on TETFund_Beneficiary_Portal \n";

        // beneficiary request ID on BI-Portal
        $bi_beneficiary_requests = SubmissionRequest::all();

        // items count
        $total_beneficiary_request_affected = 0;
        $total_bi_requests_attachments_deleted = 0;
        $total_bi_requests_count = count($bi_beneficiary_requests);

        echo ">>> Found {$total_bi_requests_count} Beneficiary Request Records on TETFund-BI-Portal \n \n \n";

        foreach($bi_beneficiary_requests as $idx=>$bi_request) {

            DB::connection($bi_db_config_name)->beginTransaction();
            $is_bi_request_affected_by_delete_operation = false;

            try {
                // bi_request_attachments_on_bi_portal portal attachment record
                $request_attachments_on_bi_portal = $bi_request->get_all_attachments($bi_request->id);
                echo ">>> Checking and comparing attachments for No. ". strval($idx+1) ." beneficiary request with id {$bi_request->id} \n \n";

                if ($request_attachments_on_bi_portal!=null && count($request_attachments_on_bi_portal) > 0) {
                    // get Iterum server beneficiary request
                    $iterum_bi_request = DB::connection($iterum_db_config_name)
                                            ->table('tf_bip_beneficiary_requests')
                                            ->where('id', $bi_request->tf_iterum_portal_key_id??null)
                                            ->first();

                    if ($iterum_bi_request!=null) {
                        // get Iterum server beneficiary request attachments
                        $iterum_request_attachments = DB::connection($iterum_db_config_name)
                                            ->table("fc_attachables")
                                            ->where('attachable_id', $iterum_bi_request->id)
                                            ->join('fc_attachments', 'fc_attachables.attachment_id', '=', 'fc_attachments.id')
                                            ->whereNull('fc_attachments.deleted_at')
                                            ->pluck('fc_attachments.id');
                        
                        if (!empty($iterum_request_attachments) && count($iterum_request_attachments)>0) {
                            // get attachments from old_tet_fund portal
                            $old_portal_attachments_by_bi_staff = DB::connection($old_tetfund_portal_db_config_name)
                                            ->table("attachments")
                                            ->where("is_uploaded_by_beneficiary", 1)
                                            ->whereIn('migrated_model_primary_id', $iterum_request_attachments)
                                            ->pluck('label');
                            
                            if (!empty($old_portal_attachments_by_bi_staff) && count($old_portal_attachments_by_bi_staff) > 0) {
                                
                                // deleting attaments with different label not in the match arrays
                                foreach ($request_attachments_on_bi_portal as $attachment_to_delete) {
                                    if (!in_array($attachment_to_delete->label, $old_portal_attachments_by_bi_staff->toArray())) {

                                        $bi_request->delete_attachment($attachment_to_delete->label);
                                        echo ">>>>>>>> \n \t Deleting {$attachment_to_delete->label} attachment not uploaded by BI-staff for the BI-request {$bi_request->id} \n >>>>>>>> \n \n \n";

                                        // indicating at least one BI request attachment was deleted during the process
                                        if ($is_bi_request_affected_by_delete_operation==false) {
                                            $is_bi_request_affected_by_delete_operation = true;
                                        }

                                        // updating flag of total attachments deleted
                                        $total_bi_requests_attachments_deleted += 1;
                                    }
                                }                               
                            }
                        }
                    }

                }
            } catch (\Exception $e) {
                DB::connection($bi_db_config_name)->rollBack();
                Log::error($e->getMessage());
                Log::error($e);
                dd($e);
            }

            // updating No of BI request affected by the process deleting attachments
            if ($is_bi_request_affected_by_delete_operation==true) {
                $total_beneficiary_request_affected += 1;
            }

            DB::connection($bi_db_config_name)->commit();
        }

        // status and success messages/response
        echo "{$total_bi_requests_count} Beneficiary requests detected \n";
        echo "{$total_bi_requests_attachments_deleted} attachments successfully deleted for {$total_beneficiary_request_affected} beneficiary requests \n";
        echo "Done Deleting Beneficiary Request Attachment Not Uploaded by BI-Staffs \n \n \n \n ";

        return 1;
    }
}
