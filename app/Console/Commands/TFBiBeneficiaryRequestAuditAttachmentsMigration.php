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

class TFBiBeneficiaryRequestAuditAttachmentsMigration extends Command
{

    protected $signature = 'tetfund:bi-beneficiary-request-audit-attachments-uploaded-by-bi-staff';    
    protected $description = 'Migration for all beneficiary request audit attachments uploaded by BI-Staffs on TETFund_BI_Portal';

    
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

        echo "\nRunning migration for all beneficiary request audit attachments uploaded by beneficiary staffs on TETFund_Beneficiary_Portal \n";

        $second_and_final_submissions = SubmissionRequest::where(function($qry) {
                return $qry->where('is_second_tranche_request', true)
                        ->orWhere('is_final_tranche_request', true);
            })->get();

        // total items counts
        $count_second_and_final_submissions_audit_attachments = 0;
        $count_second_and_final_submissions = count($second_and_final_submissions);

        // iteration over submission items
        foreach ($second_and_final_submissions as $bi_submission) {

            DB::connection($bi_db_config_name)->beginTransaction();

            try {
                if ($bi_submission->tf_iterum_portal_key_id != null) {

                    // get the beneficiary request on iterum portal
                    $iterum_beneficiary_request = DB::connection($iterum_db_config_name)
                        ->table("tf_bip_beneficiary_requests")
                        ->where('id', $bi_submission->tf_iterum_portal_key_id)
                        ->whereNull('deleted_at')
                        ->first();

                    if(!empty($iterum_beneficiary_request) && isset($iterum_beneficiary_request->parent_id) && $iterum_beneficiary_request->parent_id!=null) {

                        // get audit clearance beneficairy request
                        $iterum_beneficiary_request_audit = DB::connection($iterum_db_config_name)
                            ->table("tf_bip_beneficiary_requests")
                            ->where('requested_tranche', 'like', '%- Audit%')
                            ->where('parent_id', $iterum_beneficiary_request->parent_id)
                            ->whereNull('deleted_at')
                            ->first();

                        if (!empty($iterum_beneficiary_request_audit)) {
                            
                            // get all audit beneficiary request attachments
                            echo ">>> Fetching Iterum-Portal Audit Attachment Records for SubmissionRequest with id $bi_submission->id\n";

                            $iterum_beneficiary_request_audit_attachments = DB::connection($iterum_db_config_name)
                                            ->table("fc_attachables")
                                            ->join('fc_attachments', 'fc_attachments.id', 'fc_attachables.attachment_id')
                                            ->where('fc_attachables.attachable_id', $iterum_beneficiary_request_audit->id)
                                            ->whereNull('fc_attachments.deleted_at')
                                            ->get(['fc_attachments.*']);

                            if (!empty($iterum_beneficiary_request_audit_attachments) && count($iterum_beneficiary_request_audit_attachments) > 0) {

                                // generating desk officer email from beneficiary short-name
                                $desk_officer_email = strtolower($this->sanitize_email_prefix( optional($bi_submission->beneficiary)->short_name) . "@tetfund.gov.ng");

                                // fetching desk officer user details
                                $desk_officer = User::withTrashed()->where('email', $desk_officer_email)->first();

                                // all current submision attachment
                                $bi_submission_attachments = $bi_submission->get_all_attachments($bi_submission->id);

                                // all current submision attachment labels
                                $bi_submission_attachments_label = array_column($bi_submission_attachments, 'label');

                                foreach($iterum_beneficiary_request_audit_attachments as $iterum_audit_attachment) {

                                    // audit attachment label prefix
                                    $attachment_label_prefix = $bi_submission->is_final_tranche_request==true ? 'auditclearancefinalpaymentchecklist-' : 'auditclearancesecondtranchepaymentchecklist-';

                                    // properly formulated attachment label
                                    $formulated_label = $attachment_label_prefix. \Str::slug($iterum_audit_attachment->label);
                                    
                                    if (!in_array($formulated_label, $bi_submission_attachments_label) && !empty($desk_officer)) {
                                        
                                        // saving new attachment record
                                        $attachOBJ = new Attachment();
                                        $attachOBJ->path = $iterum_audit_attachment->path;
                                        $attachOBJ->label = $formulated_label;
                                        $attachOBJ->organization_id = $organization->id;
                                        $attachOBJ->uploader_user_id = $desk_officer->id;
                                        $attachOBJ->description = $iterum_audit_attachment->description;
                                        $attachOBJ->file_type = $iterum_audit_attachment->file_type;
                                        $attachOBJ->storage_driver = $iterum_audit_attachment->storage_driver;
                                        $attachOBJ->created_at = $iterum_audit_attachment->created_at;
                                        $attachOBJ->updated_at = $iterum_audit_attachment->updated_at;
                                        $attachOBJ->save();

                                        // saving new attachable record
                                        $attachableOBJ = new EloquentAttachable();
                                        $attachableOBJ->user_id = $desk_officer->id; //
                                        $attachableOBJ->attachment_id = $attachOBJ->id;
                                        $attachableOBJ->attachable_id = $bi_submission->id;
                                        $attachableOBJ->attachable_type = get_class($bi_submission);
                                        $attachableOBJ->created_at = $iterum_audit_attachment->created_at;
                                        $attachableOBJ->updated_at = $iterum_audit_attachment->updated_at;
                                        $attachableOBJ->save();

                                        $count_second_and_final_submissions_audit_attachments += 1;
                                        
                                        echo '>> >> >> created record for '. $formulated_label. "\n";
                                    }

                                }
                                
                                echo "\n \n \n";
                                
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

            DB::connection($bi_db_config_name)->commit();
        }

        echo "\n \n{$count_second_and_final_submissions} SECOND & FINAL tranche submission requests detected \n";
        echo "{$count_second_and_final_submissions_audit_attachments} SECOND & FINAL tranche submission requests audit attachments successfully replicated\n";
        echo "Done Migrating Submission Request Audit Attachments \n \n \n \n ";

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
