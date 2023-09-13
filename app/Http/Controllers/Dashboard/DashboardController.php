<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

use App\Managers\TETFundServer;
use App\Models\BeneficiaryMember;
use App\Models\Beneficiary;
use App\Models\SubmissionRequest;

use Hasob\FoundationCore\Models\User;
use Hasob\FoundationCore\Models\Department;
use Hasob\FoundationCore\Models\Organization;
use Hasob\FoundationCore\Models\Attachment;
use Hasob\FoundationCore\View\Components\CardDataView;
use Hasob\FoundationCore\Controllers\BaseController;

use App\Http\Requests\API\RespondCommunicationAPIRequest;
use App\DataTables\BeneficiaryMemberDatatable;
use App\DataTables\MonitoringRequestDataTable;
use Spatie\Permission\Models\Role;


class DashboardController extends BaseController
{
    

    public function index(Organization $org, Request $request) {

        $current_user = Auth()->user();
        $current_user_roles = $current_user->roles->pluck('name')->toArray();
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();

        // get some array of data from server
        $payload_data_to_rerieve = [
            'getMonitoringRequestData' => [
                    'beneficiary_id' => $beneficiary_member->beneficiary->tf_iterum_portal_key_id??null,
                ],
                
            'getBeneficiaryCommunicationData' => [
                    'beneficiary_id' => $beneficiary_member->beneficiary->tf_iterum_portal_key_id??null,
                ],
            'getBeneficiaryRequestCommunicationData' => [
                    'beneficiary_id' => $beneficiary_member->beneficiary->tf_iterum_portal_key_id??null,
                ],

            'getAllInterventionLines' => [
                    'beneficiary_type' => strtolower($beneficiary_member->beneficiary->type??null),
                ],
        ];


        // array of server data
        $tetFundServer = new TETFundServer();   /* server class constructor */
        $collection = $tetFundServer->getSomeDataArrayFromServer($payload_data_to_rerieve);

        // get beneficiary intervention lines
        $intervention_types = $collection->getAllInterventionLines??[];
        $intervention_lines = collect($intervention_types)->pluck('name', 'id')->toArray();

        // array of interventions current user roles can operate
        $get_user_can_do_interventions = SubmissionRequest::get_user_can_operate_interventions($current_user_roles);

        // interventions IDs current user can do
        $intervention_ids_user_can_do = [];
        if(!empty($get_user_can_do_interventions) && !empty($intervention_lines)) {
            foreach ($intervention_lines as $key => $intervention_line) {
                if(in_array(strtolower($intervention_line), $get_user_can_do_interventions)) {
                    // echo $get_user_can_do_interventions;
                    array_push($intervention_ids_user_can_do, $key);
                }
            }
        }

        $active_submissions = SubmissionRequest::whereIn('tf_iterum_intervention_line_key_id', $intervention_ids_user_can_do)
                        ->whereIn('status', ['not-submitted', 'submitted', 'pending-recall', 'recalled'])
                        ->where([
                            'is_monitoring_request' => false,
                            'beneficiary_id' => optional($beneficiary_member)->beneficiary_id
                        ])->orderBy('created_at', 'DESC') ->get();


        $approved_submissions = SubmissionRequest::whereIn('status', ['approved'])
                            ->whereIn('tf_iterum_intervention_line_key_id', $intervention_ids_user_can_do)
                            ->where([
                                'is_monitoring_request' => false,
                                'beneficiary_id' => optional($beneficiary_member)->beneficiary_id
                            ])->orderBy('created_at', 'DESC')->get();


        // get upcoming monitoring requests
        $upcoming_monitorings = array_filter($collection->getMonitoringRequestData??[],function($v){
                                    return $v->is_approved==true;
                                });
        
        // get official beneficiary communications
        $official_ben_communications = $collection->getBeneficiaryCommunicationData??[];

        // get official beneficairy_request communications
        $official_ben_request_communications = $collection->getBeneficiaryRequestCommunicationData??[];

        // merging all communications
        $official_communications = array_merge($official_ben_communications, $official_ben_request_communications);

        return view('dashboard.index')
                    ->with('organization', $org)
                    ->with('current_user', $current_user)
                    ->with('current_user_roles', $current_user_roles)
                    ->with('active_submissions', $active_submissions)
                    ->with('intervention_types', $intervention_types)
                    ->with('approved_submissions',$approved_submissions)
                    ->with('upcoming_monitorings', $upcoming_monitorings)
                    ->with('submission_request_obj', new SubmissionRequest())
                    ->with('official_communications', $official_communications)
                    ->with('beneficiary', optional($beneficiary_member)->beneficiary);
    }

    public function displayMonitoringDashboard(Organization $org, Request $request) {
        $current_user = Auth()->user();
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();

        // get some array of data from server
        $data_to_rerieve_payload = [
            'getAllInterventionLines' => [
                    'beneficiary_type' => $beneficiary_member->beneficiary->type ?? null
                ],

            'getBeneficiaryApprovedMonitorings' => [
                    'beneficiary_id' => $beneficiary_member->beneficiary->tf_iterum_portal_key_id ?? null,
                ],
        ];

        $tetFundServer = new TETFundServer();   /* server class constructor */
        $some_server_data_array = $tetFundServer->getSomeDataArrayFromServer($data_to_rerieve_payload);

        // beneficiary intervention lines
        $intervention_types_server_response = $some_server_data_array->getAllInterventionLines;
        
        // beneficiary approved monitoring request
        $beneficiary_approved_monitorings = $some_server_data_array->getBeneficiaryApprovedMonitorings;

        // updating monitoring request status to approved where (is-approved is true)
        if (count($beneficiary_approved_monitorings) > 0) {
            $array_of_ids = array_column($beneficiary_approved_monitorings, 'id');
            SubmissionRequest::whereNotNull('tf_iterum_portal_key_id')
                            ->where('is_monitoring_request', true)
                            ->whereIn('tf_iterum_portal_key_id', $array_of_ids)
                            ->update(['status' => 'approved']); 
        }

        $intervention_lines = [];
        foreach($intervention_types_server_response as $idx=>$item){
            $intervention_lines [$item->id]= $item->name;
        }

        $cdv_submission_requests = new CardDataView(SubmissionRequest::class, "pages.monitoring.card_view_item", $intervention_lines);
        $cdv_submission_requests->setDataQuery([
                    'organization_id' => $org->id,
                    'beneficiary_id' => $beneficiary_member->beneficiary_id, 
                    'is_monitoring_request' => true 
                ])
                ->addDataGroup('All','deleted_at',null)
                ->addDataGroup('Not Submitted','status','not-submitted')
                ->addDataGroup('Submitted','status','submitted')
                ->addDataGroup('Approved','status','approved')
                ->enableSearch(true)
                ->addDataOrder('created_at', 'DESC')
                ->enablePagination(true);

        if (request()->expectsJson()){
            return $cdv_submission_requests->render();
        }

        return view('pages.monitoring.card_view_index')
                    ->with('organization', $org)
                    ->with('current_user', $current_user)
                    ->with('cdv_submission_requests', $cdv_submission_requests);
    }

    public function displayFundAvailabilityDashboard(Organization $org, Request $request) {

        $current_user = Auth()->user();
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();
        $bi_beneficiary = $beneficiary_member->beneficiary;
        $tf_beneficiary_id = $bi_beneficiary->tf_iterum_portal_key_id;

        $selected_year = date('Y');
        if (isset($request->year) &&  $request->year!=null && is_numeric( $request->year)) {
            $selected_year = $request->year;
        }

        //Get the funding data for the selected year.
        $tetFundServer = new TETFundServer();   /* server class constructor */
        $funding = $tetFundServer->getFundAvailabilityData($tf_beneficiary_id, null, [$selected_year]);

        return view('pages.fund_availability.index')
                ->with('organization', $org)
                ->with("funding", (array) $funding)
                ->with("selected_year", $selected_year) 
                ->with("beneficiary", $bi_beneficiary)
                ->with('current_user', $current_user);
    }

    public function getBiInterventionAllocatedFundsData(Organization $org, Request $request) {
        // decide fund availability type to be retrived
        $years = [];
        $is_astd_intervention = false;
        $is_first_tranche_intervention = false;

        // get selected intervention years
        for ($i = 1; $i <= 4 ; ++$i) {
            $intervention_year = $request->get('intervention_year'.$i);
            if(!empty($intervention_year)) {
                array_push($years, $intervention_year);
            }
        }

        if (SubmissionRequest::is_astd_intervention(optional($request)->intervention_name)) {
            $is_astd_intervention = true;
            $data_to_rerieve_payload['getASTDFundAvailabilityData'] = [
                'beneficiary_id' => $request->get('tf_beneficiary_id')??'',
                'intervention_name' => $request->get('intervention_name')??'',
                'intervention_line_id' => $request->get('tf_intervention_id')??'',
                // 'allocation_details'=>true
            ];

        } elseif (SubmissionRequest::is_start_up_first_tranche_intervention(optional($request)->intervention_name)) {

            $is_first_tranche_intervention = true;
            $data_to_rerieve_payload['getFirstTranchBasedFundAvailabilityData'] = [
                'beneficiary_id' => $request->get('tf_beneficiary_id')??'',
                'intervention_name' => $request->get('intervention_name')??'',
                'intervention_line_id' => $request->get('tf_intervention_id')??'',
                // 'allocation_details'=>true
            ];

        } else {
            $data_to_rerieve_payload['getFundAvailabilityData'] = [
                    'beneficiary_id' => $request->get('tf_beneficiary_id')??'',
                    'years' => $years,
                    'tf_iterum_intervention_line_key_id' => $request->get('tf_intervention_id')??'',
                    // 'allocation_details'=>true
                ];
        }

        $tetFundServer = new TETFundServer();   /* server class constructor */
        $some_server_data_array = $tetFundServer->getSomeDataArrayFromServer($data_to_rerieve_payload);

        //Get the funding data and total_funds for the selected intervention year(s) if non-astd.
        $submission_allocations = $some_server_data_array->getFundAvailabilityData??null;
        if ($is_astd_intervention==true) {
            $submission_allocations = $some_server_data_array->getASTDFundAvailabilityData??null;
        } elseif ($is_first_tranche_intervention==true) {
            $submission_allocations = $some_server_data_array->getFirstTranchBasedFundAvailabilityData??null;
        }

        return $this->sendResponse($submission_allocations, 'The Beneficiary Intervention Allocation Has Been Retrived Successfully.');
    }

    public function displayDeskOfficerAdminDashboard(Organization $org, BeneficiaryMemberDatatable $beneficiaryMembersDatatable) {
        $current_user = Auth()->user();

        /** @var BeneficiaryMember $beneficiary_member */
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();
        
        if (empty($beneficiary_member) || $beneficiary_member->beneficiary == null) {
            //Flash::error('BeneficiaryMember not found');
            return redirect(route('tf-bi-portal.beneficiaries.index'));
        }

        $allRoles = Role::where('guard_name', 'web')
                    ->where('name', '!=', 'admin')
                    ->where('name', '!=', 'BI-desk-officer')
                    ->where('name', 'like', 'bi-%')
                    ->pluck('name');
        
        return $beneficiaryMembersDatatable->with('beneficiary_id', $beneficiary_member->beneficiary->id)
                ->render('tf-bi-portal::pages.desk_officer.index', [
                    'beneficiary'=>$beneficiary_member->beneficiary,
                    'geo_zone_list'=>BaseController::geoZoneList(),
                    'states_list'=>BaseController::statesList(),
                    'organization'=>$org,
                    'current_user'=>$current_user,
                    'roles'=>$allRoles
                ]);
    }

    public function displayLibrarianAdminDashboard(Organization $org, Request $request) {

        $current_user = Auth()->user();

        return view('pages.librarian.index')
                    ->with('organization', $org)
                    ->with('current_user', $current_user);
    }

    public function displayDirectorICTAdminDashboard(Organization $org, Request $request) {

        $current_user = Auth()->user();

        return view('pages.director_ict.index')
                    ->with('organization', $org)
                    ->with('current_user', $current_user);
    }

    public function displayDirectorPIWorksAdminDashboard(Organization $org, Request $request) {

        $current_user = Auth()->user();

        return view('pages.director_works.index')
                    ->with('organization', $org)
                    ->with('current_user', $current_user);
    }

    // display document from API Endpoint Response
    public function displayResponseAttachment(Organization $org, Request $request) {
        if (isset($request->path) && isset($request->label) && isset($request->file_type) && isset($request->storage_driver)) {

            if ($request->storage_driver == 'azure' || $request->storage_driver == 's3') {
                return \Illuminate\Support\Facades\Storage::disk($request->storage_driver)->download(
                    $request->path,
                    $request->label,
                    ['Content-Disposition' => 'inline; filename="' . $request->label . '"']
                );
            }

            return response()->file(base_path($request->path));
        }        
    } 

    // process communication response
    public function processCommunicationResponse(Organization $org, RespondCommunicationAPIRequest $request) {

        $communication_table_type = null;
        if ($request->communication_table_type == 'tf_bip_ben_req_communications') {
            $communication_table_type = SubmissionRequest::where('id', $request->communication_parent_id)
                                        ->orWhere('tf_iterum_portal_key_id', $request->communication_parent_id)
                                        ->first();
        } elseif ($request->communication_table_type == 'tf_bm_beneficiary_communications') {
            $communication_table_type = Beneficiary::find($request->communication_parent_id);
        } else {
            return $this->sendError('The Communication Table Type Is Invalid.');
        }

        $current_user = auth()->user();

        // handling array of file attachments
        $attachments_container = [];
        if($request->hasfile('communication_attachments')){
            $attach_ct = 0;
            foreach($request->file('communication_attachments') as $file){
                $label = 'Communication Response Attachment No. '. ++$attach_ct;
                $discription = 'This Document Contains the ' . $label;

                $communication_attachable = $communication_table_type->attach($current_user, $label, $discription, $file);
                
                $original_data = [];
                $original_data['id'] = $communication_attachable->attachment->id;
                $original_data['uploader_user_id'] = $communication_attachable->attachment->uploader_user_id;
                $original_data['path'] = $communication_attachable->attachment->path;
                $original_data['path_type'] = $communication_attachable->attachment->path_type;
                $original_data['label'] = $communication_attachable->attachment->label;
                $original_data['description'] = $communication_attachable->attachment->description;
                $original_data['file_type'] = $communication_attachable->attachment->file_type;
                $original_data['file_number'] = $communication_attachable->attachment->file_number;
                $original_data['storage_driver'] = $communication_attachable->attachment->storage_driver;
                $original_data['allowed_viewer_user_ids'] = $communication_attachable->attachment->allowed_viewer_user_ids;
                $original_data['allowed_viewer_user_roles'] = $communication_attachable->attachment->allowed_viewer_user_roles;
                $original_data['allowed_viewer_user_departments'] = $communication_attachable->attachment->allowed_viewer_user_departments;
                $original_data['organization_id'] = $communication_attachable->attachment->organization_id;
                $original_data['deleted_at'] = $communication_attachable->attachment->deleted_at;
                $original_data['created_at'] = $communication_attachable->attachment->created_at;
                $original_data['updated_at'] = $communication_attachable->attachment->updated_at;

                array_push($attachments_container, $original_data);
            }
        }

        // sending to server
        $pay_load = [
            '_method' => 'POST',
            'user_email' => $current_user->email,
            'communication_parent_id' => $communication_table_type->tf_iterum_portal_key_id ?? null,
            'communication_primary_id' => $request->communication_primary_id,
            'communication_table_type' => $request->communication_table_type ?? null,
            'communication_comment' => $request->communication_comment ?? null,
            'communication_attachments' => $attachments_container,
        ];

        $tetFundServer = new TETFundServer();   /* server class constructor */
        $tf_processed_communication_response  = $tetFundServer->processCommunicationResponse($pay_load);

        if ($tf_processed_communication_response == true) {
            return $this->sendSuccess('Communication Response Processed and Successfully Saved!');
        }

        return $this->sendError('An unknown error was encountered while processing communication response.');
    }   


}
