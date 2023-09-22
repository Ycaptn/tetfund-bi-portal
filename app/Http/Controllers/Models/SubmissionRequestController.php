<?php

namespace App\Http\Controllers\Models;

use Log;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Managers\TETFundServer;

use App\Models\BeneficiaryMember;
use App\Models\NominationRequest;
use App\Models\SubmissionRequest;

use App\Events\SubmissionRequestCreated;
use App\Events\SubmissionRequestDeleted;

use App\Events\SubmissionRequestUpdated;
use Hasob\FoundationCore\Models\Organization;
use App\DataTables\BindedNominationsDataTable;

use App\DataTables\SubmissionRequestDataTable;

use Hasob\FoundationCore\Controllers\BaseController;
use App\Http\Requests\CreateSubmissionRequestRequest;
use App\Http\Requests\UpdateSubmissionRequestRequest;
use Hasob\FoundationCore\View\Components\CardDataView;
use App\Http\Requests\ProcessAttachmentsSubmissionRequest;


class SubmissionRequestController extends BaseController
{
    /**
     * Display a listing of the SubmissionRequest.
     *
     * @param SubmissionRequestDataTable $submissionRequestDataTable
     * @return Response
     */
    private $id = null;
    public function index(Organization $org, SubmissionRequestDataTable $submissionRequestDataTable) {
        $current_user = Auth()->user();
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();

        // get some array of data from server
        $data_to_rerieve_payload = [
            'getAllInterventionLines' => [
                    'beneficiary_type' => $beneficiary_member->beneficiary->type ?? null
                ],

            'getBeneficiaryApprovedSubmissions' => [
                    'beneficiary_id' => $beneficiary_member->beneficiary->tf_iterum_portal_key_id ?? null,
                ],
        ];

        $tetFundServer = new TETFundServer();   /* server class constructor */
        $some_server_data_array = $tetFundServer->getSomeDataArrayFromServer($data_to_rerieve_payload);

        // beneficiary intervention lines
        $intervention_types_server_response = $some_server_data_array->getAllInterventionLines ?? [];
        
        // beneficiary approved(has_aip || has_disbursement_memo) submission request
        $beneficiary_approved_submissions = $some_server_data_array->getBeneficiaryApprovedSubmissions ?? [];

        // updating submission request status to approved where (has_aip || has_disbursement_memo)
        if (count($beneficiary_approved_submissions) > 0) {
            $array_of_ids = array_column($beneficiary_approved_submissions, 'id');
            SubmissionRequest::whereNotNull('tf_iterum_portal_key_id')
                            ->where('is_monitoring_request', false)
                            ->whereIn('tf_iterum_portal_key_id', $array_of_ids)
                            ->update(['status' => 'approved']); 
        }

        $intervention_lines = [];
        foreach($intervention_types_server_response as $idx=>$item){
            $intervention_lines [$item->id]= $item->name;
        }

        $current_user_roles = $current_user->roles->pluck('name')->toArray();
        $get_user_can_do_interventions = SubmissionRequest::get_user_can_operate_interventions($current_user_roles);

        $intervention_ids_user_can_do = [];
        if(!empty($get_user_can_do_interventions) && !empty($intervention_lines)) {
            foreach ($intervention_lines as $key => $intervention_line) {
                if(in_array(strtolower($intervention_line), $get_user_can_do_interventions)) {
                    array_push($intervention_ids_user_can_do, $key);
                }
            }
        }

        $cdv_submission_requests = new CardDataView(SubmissionRequest::class, "pages.submission_requests.card_view_item", $intervention_lines);
        $cdv_submission_requests->setDataQuery([
                            'organization_id' => $org->id,
                            'is_monitoring_request' => false,
                            'beneficiary_id' => optional($beneficiary_member)->beneficiary_id,
                        ])
                        ->addDataGroup('All','deleted_at',null)
                        ->addDataGroup('Not Submitted','status','not-submitted')
                        ->addDataGroup('Submitted','status','submitted')
                        ->addDataGroup('Approved','status','approved')
                        ->addDataGroup('Recalled','status','recalled')
                        ->enableSearch(true)
                        ->addDataOrder('created_at', 'DESC')
                        ->enablePagination(true)
                        ->enableFilter(true)
                        // ->addFilterRelatedFields('tf_iterum_intervention_line_key_id', $intervention_ids_user_can_do)
                        ->addFilterGroupRangeSelect('Amount Requested', 'request_amount', 1,1000000000,"<")
                        ->addFilterGroupDateRangeSelect('Date Submitted', 'created_at')
                        ->addFilterGroupMultipleSelect(
                            'Intervention Years', 
                            'intervention_year1,intervention_year2,intervention_year3,intervention_year4', 
                            array_combine(range(2016, date('Y')), range(2016, date('Y')))
                        )->addFilterGroupSingleSelect(
                            'Intervention Type', 
                            'tf_iterum_intervention_line_key_id', 
                            $intervention_lines
                        )->setPaginationLimit(20)
                        ->setSearchPlaceholder('Search Submissions by Project Title');

        if (request()->expectsJson()){
            return $cdv_submission_requests->render();
        }

        return view('pages.submission_requests.card_view_index')
                    ->with('current_user', $current_user)
                    ->with('current_user_roles', $current_user_roles)
                    ->with('submission_request_obj', new SubmissionRequest())
                    ->with('cdv_submission_requests', $cdv_submission_requests)
                    ->with('beneficiary_type_intervention_lines', $intervention_lines);
    }

    /**
     * Show the form for creating a new SubmissionRequest.
     *
     * @return Response
     */
    public function create(Organization $org) {
        $current_user = auth()->user();
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();
        $beneficiary = optional($beneficiary_member)->beneficiary;
        $submissionRequest = new SubmissionRequest();

        $pay_load = [
            '_method' => 'GET',
            'beneficiary_type' => optional($beneficiary)->type,
            'interventions_to_skip' => $submissionRequest->interventions_denied_submission()
        ];

        $tetFundServer = new TETFundServer();   /* server class constructor */
        $intervention_types_server_response = $tetFundServer->get_all_data_list_from_server('tetfund-ben-mgt-api/interventions', $pay_load);

          $years = [];
          for ($i=0; $i < 7; $i++) { 
              array_push($years, date("Y")-$i);
          }

        return view('pages.submission_requests.create')
            ->with("years", $years)
            ->with("beneficiary", $beneficiary)
            ->with("current_user", $current_user)
            ->with("intervention_types", $intervention_types_server_response);
    }

    /**
     * Store a newly created SubmissionRequest in storage.
     *
     * @param CreateSubmissionRequestRequest $request
     *
     * @return Response
     */
    public function store(Organization $org, CreateSubmissionRequestRequest $request) {
        $input = $request->all();

        $input['intervention_year1'] = 0;
        $input['intervention_year2'] = 0;
        $input['intervention_year3'] = 0;
        $input['intervention_year4'] = 0;

        $years = [];
        if ($request->intervention_year1 != null) {
            array_push($years, $request->intervention_year1);
        }

        if ($request->intervention_year2 != null) {
            array_push($years, $request->intervention_year2);
        }

        if ($request->intervention_year3 != null) {
            array_push($years, $request->intervention_year3);
        }

        if ($request->intervention_year4 != null) {
            array_push($years, $request->intervention_year4);
        }
        $years_unique = array_unique($years);
        sort($years_unique);

        $counter = 1;
        foreach($years_unique as $year) {
            $input['intervention_year'.$counter] = $year;
            $counter += 1;
        }

        $current_user = auth()->user();
        $current_user_roles = $current_user->roles->pluck('name')->toArray();
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();
        // checki if a similar request does exit
        if (!SubmissionRequest::is_astd_intervention($request->intervention_name) && !SubmissionRequest::is_start_up_first_tranche_intervention($request->intervention_name) && $beneficiary_member->beneficiary->hasRequest($request->tf_iterum_intervention_line_key_id, $input['intervention_year1'], $input['intervention_year2'], $input['intervention_year3'], $input['intervention_year4'], null, $request->intervention_name)) {
                $error_msg = "A previous submission request for one or more of the selected years has already been submitted.";
                return redirect()->back()->withErrors([$error_msg])->withInput();
        }

        // checking if user has the privillege to store this request
        if (!(SubmissionRequest::can_user_operate_intervention($current_user_roles, $request->get('intervention_name')))) {
            return redirect()->back()->withError('User does not have permission to access the Submission Request!')->withInput();
        }

        // setting up sadditional request parameters for interventions that starts with first tranche
        if (SubmissionRequest::is_start_up_first_tranche_intervention($request->intervention_name)) {
            $new_properties =  [
                'request_tranche' => '1st Tranche Payment',
                'is_first_tranche_request' => true,
            ];

            $request->merge($new_properties);
        }

        $type_surfix = $request->request_tranche ?? 'Request for AIP';

        if (SubmissionRequest::is_astd_intervention($request->intervention_name) == true) {
            $type_surfix = 'Request For Fund';
        }

        $input['type'] = $type_surfix;
        $input['status'] = 'not-submitted';
        $input['title'] = $input['intervention_name']. ' - '. $type_surfix .' (' .implode(', ', $years_unique) .')';
        $input['requesting_user_id'] = $current_user->id;
        $input['organization_id'] = $current_user->organization_id;
        $input['beneficiary_id'] = $beneficiary_member->beneficiary_id;

        if (!$request->has('is_first_tranche_request') && !$request->has('is_second_tranche_request') && !$request->has('is_third_tranche_request') && !$request->has('is_final_tranche_request') && !$request->has('is_monitoring_request')) {
            $input['is_aip_request'] = true;
        }

        $input['is_first_tranche_request'] = $request->is_first_tranche_request ?? false;
        $input['is_second_tranche_request'] = $request->is_second_tranche_request ?? false;
        $input['is_third_tranche_request'] = $request->is_third_tranche_request ?? false;
        $input['is_final_tranche_request'] = $request->is_final_tranche_request ?? false;
        $input['is_monitoring_request'] = $request->is_monitoring_request ?? false;

        /** @var SubmissionRequest $submissionRequest */
        $submissionRequest = SubmissionRequest::create($input);

        /*Dispatch event*/
        SubmissionRequestCreated::dispatch($submissionRequest);
        return redirect(route('tf-bi-portal.submissionRequests.show', $submissionRequest->id))->with('success', 'Submission Request saved successfully.')->with('submissionRequest', $submissionRequest);
    }


    /* implement processing success */
    public function processSubmissionRequestAttachment(ProcessAttachmentsSubmissionRequest $request, $id) {
        $any_new_attachment = false;
        $attachment_inputs = $request->all();
        $submissionRequest = SubmissionRequest::find($request->id);

        if (empty($submissionRequest)) {
            return redirect()->route('tf-bi-portal.submissionRequests.index')->withError('Oops... The submission request was not found');
        }

        // intervention years
        $intervention_years = $submissionRequest->getInterventionYears();

        // intervention checklist group name
        $checklist_group_name = self::generateCheckListGroupName($request->intervention_line_name??'', $submissionRequest);

        // get audit checklist for tranches applicable
        $additional_checklists_payload = [
                '_method' => 'POST',
                'applicable_years' => $intervention_years
            ];

        if ($submissionRequest->is_second_tranche_request || $submissionRequest->is_final_tranche_request) {
            $additional_checklists_payload['checklist_group_name_audit'] = self::generateCheckListGroupName($request->intervention_line_name??'', $submissionRequest, true);
        }


        // applicable request type
        $lower_case_intervention_name = strtolower($request->intervention_line_name??'');
        if ($submissionRequest->is_aip_request && (str_contains($lower_case_intervention_name, "physical infrastructure") || str_contains($lower_case_intervention_name, "zonal intervention")) && in_array(2023, $intervention_years)) {

            $applicable_types = $this->getAppplicableRequestType($submissionRequest, $request->intervention_line_name??'');

            $additional_checklists_payload['has_procurement'] = $applicable_types['has_procurement'];
            $additional_checklists_payload['has_construction'] = $applicable_types['has_construction'];
        }

        
        // get checklist for specified intervention
        $tetFundServer = new TETFundServer();   /* server class constructor */
        $checklist_items = (array)$tetFundServer->getInterventionChecklistData($checklist_group_name, $additional_checklists_payload);
        
        //mapping checklist id to item_label 
        foreach ($checklist_items as $checklist){
            $checklist_items_arr[$checklist->id] = $checklist->item_label;
            if (str_contains(strtolower($checklist->list_name), 'auditclearancesecondtranchepaymentchecklist')) { 
                $checklist_items_arr[$checklist->id] = 'auditclearancesecondtranchepaymentchecklist-'.$checklist->item_label;
            } elseif  (str_contains(strtolower($checklist->list_name), 'auditclearancefinalpaymentchecklist')) {
                $checklist_items_arr[$checklist->id] = 'auditclearancefinalpaymentchecklist-'.$checklist->item_label;
            }
        }

        //processing individual checklist file uploads
        if($request->checklist_input_fields != "") {
            $checklist_input_fields_arr = explode(',', $request->checklist_input_fields);
            foreach ($checklist_input_fields_arr as $checklist_input_name) {
                if (isset($attachment_inputs[$checklist_input_name]) && $request->hasFile($checklist_input_name)) {
                    $checklist_id = substr("$checklist_input_name",10);
                    $checklist_id = str_replace('checklist-', '', $checklist_input_name);

                    $label = Str::slug($checklist_items_arr[$checklist_id]);
                    $label = Str::limit($label ,495, "");
                    $concate_description_label = str_replace('auditclearancefinalpaymentchecklist-', '', $label);
                    $concate_description_label = str_replace('auditclearancesecondtranchepaymentchecklist-', '', $concate_description_label);
                    $discription = 'This Document Contains the ' . $concate_description_label;

                    $submissionRequest->attach(auth()->user(), $label, $discription, $attachment_inputs[$checklist_input_name]);
                    $any_new_attachment = true;
                }
            }
        }

        //handling additional files submission
        if (isset($request->additional_attachment) && $request->hasFile('additional_attachment')) {
            $label = Str::limit($request->additional_attachment_name.' Additional Attachment', 495, ""); 
            $any_new_attachment = true;
            $discription = 'This Document Contains the ' . $label ;
            $submissionRequest->attach(auth()->user(), $label, $discription, $attachment_inputs['additional_attachment']);
        }   


        if ($any_new_attachment) {
            $success_message = 'Submission Request Attachments saved successfully!';
            return redirect()->back()->with('success', $success_message);
        }

        $error_message = 'No new file selection was made or provided for this Submission Request!';
        return redirect()->back()->with('error', $error_message);
    
    }


    /* implement processing success */
    public function processSubmissionRequestToTFPortal(Request $request) {
        $input = $request->all();
        $submissionRequest = SubmissionRequest::find($input['submission_request_id']);
        $beneficiary = $submissionRequest->beneficiary;
        $errors_array = array();    /* errors flag */
        $years = array();   /*intervention years*/

        if (empty($submissionRequest)) {
            return redirect(route('tf-bi-portal.submissionRequests.index'));
        }

        $current_user = auth()->user();
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();

        if(!$submissionRequest->can_user_operate_intervention($current_user->getRoleNames()->toArray(), $request->get('intervention_name'))) {
            return redirect()->back()->with('error', 'Please, Kindly Contact the Institution TETFund Desk Officer or any other users who has the priviledge to make a submission for the selected intervention');
        }
        
        //intervention years merge
        if ($submissionRequest->intervention_year1 != null) {
            array_push($years, $submissionRequest->intervention_year1);
        }
        if ($submissionRequest->intervention_year2 != null) {
            array_push($years, $submissionRequest->intervention_year2);
        }
        if ($submissionRequest->intervention_year3 != null) {
            array_push($years, $submissionRequest->intervention_year3);
        }
        if ($submissionRequest->intervention_year4 != null) {
            array_push($years, $submissionRequest->intervention_year4);
        }

        // initializing submission payload
        $pay_load = $submissionRequest->toArray();

        // applicable request type
        $lower_case_intervention_name = strtolower(optional($request)->intervention_name);
        if ($submissionRequest->is_aip_request && (str_contains($lower_case_intervention_name, "physical infrastructure") || str_contains($lower_case_intervention_name, "zonal intervention")) && in_array(2023,$years)) {
            
            $applicable_request_types = $this->getAppplicableRequestType($submissionRequest, optional($request)->intervention_name);

            $pay_load['has_procurement'] = $applicable_request_types['has_procurement'];
            $pay_load['has_construction'] = $applicable_request_types['has_construction'];
        }


        $guessed_intervention_name = explode('-', $submissionRequest->title);
        if ($submissionRequest->is_aip_request==true || ($submissionRequest->is_first_tranche_request==true && $submissionRequest->is_start_up_first_tranche_intervention(trim($guessed_intervention_name[0])))) {
            
            //get total fund available 
            if ($submissionRequest->is_astd_intervention(optional($request)->intervention_name)) {

                $tetFundServer = new TETFundServer();   /* server class constructor */
                $some_server_data_array = $tetFundServer->getSomeDataArrayFromServer(['getASTDFundAvailabilityData' => [
                    'beneficiary_id' => $beneficiary->tf_iterum_portal_key_id,
                    'intervention_name' => optional($request)->intervention_name,
                    'intervention_line_id' => $submissionRequest->tf_iterum_intervention_line_key_id
                ]]);

                //Get the ASTD funding data and total_funds.
                $fund_availability = $some_server_data_array->getASTDFundAvailabilityData ?? null;

            } elseif ($submissionRequest->is_first_tranche_request && $submissionRequest->is_start_up_first_tranche_intervention(optional($request)->intervention_name)) {
            
                // decide fund availability type to be retrived is First Tranch based
                $tetFundServer = new TETFundServer();   /* server class constructor */
                $some_server_data_array = $tetFundServer->getSomeDataArrayFromServer(['getFirstTranchBasedFundAvailabilityData' => [
                    'beneficiary_id' => $beneficiary->tf_iterum_portal_key_id,
                    'intervention_name' => optional($request)->intervention_name,
                    'intervention_line_id' => $submissionRequest->tf_iterum_intervention_line_key_id
                ]]);
                
                //Get the first tranche based funding data and total_funds.
                $fund_availability = $some_server_data_array->getFirstTranchBasedFundAvailabilityData ?? null;

            } else {
                $tetFundServer = new TETFundServer();   /* server class constructor */
                $fund_availability = $tetFundServer->getFundAvailabilityData($beneficiary->tf_iterum_portal_key_id, $submissionRequest->tf_iterum_intervention_line_key_id, $years, true);

                //error when no fund allocation for selected year(s) is found
                if (isset($fund_availability->success) && $fund_availability->success == false && $fund_availability->message != null) {
                    array_push($errors_array, $fund_availability->message);
                }            
            }

            if (isset($fund_availability->total_funds) && $fund_availability->total_funds != floatval($submissionRequest->amount_requested) && $submissionRequest->is_aip_request==true && !$submissionRequest->is_astd_intervention($request->get('intervention_name'))) {
            
                //error for requested fund mismatched to allocated fund non-astd interventions
                array_push($errors_array, "Fund requested must be equal to the Allocated amount.");
                          
            } else if (isset($fund_availability->total_available_fund) && floatval($submissionRequest->amount_requested) > $fund_availability->total_available_fund && ($submissionRequest->is_astd_intervention($request->get('intervention_name')) || ($submissionRequest->is_first_tranche_request && $submissionRequest->is_start_up_first_tranche_intervention($request->get('intervention_name'))))) {
                
                //error for requested fund mismatched to allocated fund for all ASTD interventions
                array_push($errors_array, "Fund requested cannot be greater than allocated/available amount.");
            }

            //error when at least one selected allocation year is found
            if (isset($fund_availability->allocation_records) && count($fund_availability->allocation_records) > 0 && !$submissionRequest->is_astd_intervention($request->get('intervention_name')) && !($submissionRequest->is_first_tranche_request && $submissionRequest->is_start_up_first_tranche_intervention($request->get('intervention_name')))) {
                $all_valid_allocation_year = array_column($fund_availability->allocation_records, 'year');
                foreach($years as $year) {
                    if (!in_array($year, $all_valid_allocation_year)) {
                        array_push($errors_array, "No allocation datails is found for selected Intervention year ". $year);
                    }
                }
            }

            if ($submissionRequest->is_aip_request==true && !$submissionRequest->is_astd_intervention(optional($request)->intervention_name) && !($submissionRequest->is_first_tranche_request && $submissionRequest->is_start_up_first_tranche_intervention($request->get('intervention_name'))) && isset($fund_availability->allocation_records) && count($fund_availability->allocation_records) > 0) {
                foreach($fund_availability->allocation_records as $allocation) {
                    if($allocation->utilization_status != null && $allocation->utilization_status == 'utilized') {
                        array_push($errors_array, "Allocated fund of ₦".number_format($allocation->allocated_amount, 2) ." for ". $allocation->year . " intervention year has been utilized.");
                    }
                }
            }
            
        } else {
            $pay_load['tf_iterum_aip_request_id'] = $submissionRequest->getParentAIPSubmissionRequest()->tf_iterum_portal_key_id ?? null;
        }

        //error for incomplete attachments
        if ($submissionRequest->get_all_attachments_count_aside_additional($submissionRequest->id, 'Additional Attachment') < $input['checklist_items_count']) {
            array_push($errors_array, "The request submission must contain all required Attachment(s).");
        }

        //checking errors status
        if (count($errors_array) > 0) {
            return redirect()->back()->withErrors($errors_array);
        }
        
        //processing submission to tetfund server
        $tf_beneficiary_id = $beneficiary->tf_iterum_portal_key_id;
        $pay_load['tf_beneficiary_id'] = $tf_beneficiary_id;
        $pay_load['_method'] = 'POST';
        $pay_load['submission_user'] = $current_user;

        // resetting value for requested amount when is a fiorst tranch base intervention
        if($submissionRequest->is_first_tranche_request==true && $submissionRequest->is_start_up_first_tranche_intervention(optional($request)->intervention_name)) {
           
            $first_tranche_percentage = $submissionRequest->first_tranche_intervention_percentage(optional($request)->intervention_name);
            $percentage = $first_tranche_percentage!=null ? str_replace('%', '', $first_tranche_percentage) : 0;
            // $pay_load['amount_requested'] = ($percentage * $submissionRequest->amount_requested)/100 ?? 0;
        }

        // add attachment records to payload
        $submission_attachment_array = $submissionRequest->get_all_attachments($input['submission_request_id']);
        $pay_load['submission_attachment_array'] = ($submission_attachment_array != null) ? $submission_attachment_array : [];
        // add nomination details and attachements to payload
        $intervention_name = '';
        $nomination_table = '';
        if (str_contains(strtolower(optional($request)->intervention_name), 'teaching practice')) {
            $intervention_name = 'tp';
            $nomination_table = 'tp_submission';
        } elseif (str_contains(strtolower(optional($request)->intervention_name), 'conference attendance')) {
            $intervention_name = 'ca';
            $nomination_table = 'ca_submission';
        } elseif (str_contains(strtolower(optional($request)->intervention_name), 'tetfund scholarship')) {
            $intervention_name = 'tsas';
            $nomination_table = 'tsas_submission';
        }

        // execute for ASTD Interventions only
        if ($submissionRequest->is_astd_intervention(optional($request)->intervention_name)==true) {
            $final_nominations_arr = NominationRequest::with($nomination_table)
                    ->with('attachables.attachment')
                    ->where('bi_submission_request_id', null)
                    ->where('beneficiary_id', $beneficiary_member->beneficiary_id)
                    ->where('type', $intervention_name)
                    ->where('head_of_institution_checked_status', 'approved')
                    ->get();
            if($nomination_table == "ca_submission"){
                $filtered_nomination_arr = $final_nominations_arr->filter(function($item){
                    $today = strtotime(now());
                    $conference_start_date = strtotime($item->ca_submission->conference_start_date);    
                    $month_difference = ($conference_start_date - $today) / (3600 *24 *30);
                    return ($month_difference >= 2);
                });
                $final_nominations_arr = $filtered_nomination_arr;
            }
            $pay_load['final_nominations_arr'] = $final_nominations_arr;
            $pay_load['nomination_table'] = $nomination_table;
        }


        $tetFundServer = new TETFundServer();   /* server class constructor */
        $final_submission_to_tetfund = $tetFundServer->processSubmissionRequest($pay_load, $tf_beneficiary_id);

        // process in cases of ongoing submission
        if(isset($final_submission_to_tetfund->data->array_of_generated_tranches) && !empty($final_submission_to_tetfund->data->array_of_generated_tranches)) {
            $parent_aip_request = null;

            foreach ($final_submission_to_tetfund->data->array_of_generated_tranches as $generated_tranche) {

                $submission_tranche = SubmissionRequest::where('tf_iterum_portal_key_id', $generated_tranche->id)->first();
                if (empty($submission_tranche)) {
                    $submission_tranche = new SubmissionRequest();
                }

                $submission_tranche->organization_id = $submissionRequest->organization_id;
                $submission_tranche->title = $generated_tranche->title;
                $submission_tranche->status = 'submitted';
                $submission_tranche->type = $generated_tranche->requested_tranche;
                $submission_tranche->requesting_user_id = $submissionRequest->requesting_user_id;
                $submission_tranche->beneficiary_id = $submissionRequest->beneficiary_id;
                $submission_tranche->intervention_year1 = $submissionRequest->intervention_year1;
                $submission_tranche->intervention_year2 = $submissionRequest->intervention_year2;
                $submission_tranche->intervention_year3 = $submissionRequest->intervention_year3;
                $submission_tranche->intervention_year4 = $submissionRequest->intervention_year4;
                $submission_tranche->proposed_request_date = $submissionRequest->proposed_request_date;
                $submission_tranche->tf_iterum_portal_key_id = $generated_tranche->id;
                $submission_tranche->tf_iterum_portal_request_status = $generated_tranche->request_status;
                $submission_tranche->tf_iterum_portal_response_meta_data = json_encode($generated_tranche);
                $submission_tranche->tf_iterum_portal_response_at = date('Y-m-d H:i:s');
                $submission_tranche->amount_requested = $generated_tranche->request_amount;
                $submission_tranche->tf_iterum_intervention_line_key_id = $submissionRequest->request_amount;

                if($generated_tranche->is_aip_request==true) {
                    $submission_tranche->parent_id = null;
                    $submission_tranche->is_aip_request = true;
                    $submission_tranche->save();

                    $parent_aip_request = $submission_tranche;
                } else {
                    $submission_tranche->is_first_tranche_request = $generated_tranche->is_first_tranche_request;
                    $submission_tranche->is_second_tranche_request = $generated_tranche->is_second_tranche_request;
                    $submission_tranche->is_final_tranche_request = $generated_tranche->is_final_tranche_request;
                    $submission_tranche->parent_id = optional($parent_aip_request)->id;
                    $submission_tranche->save();
                }
            }
            
            // settting the parent_id for primary request
            $submissionRequest->parent_id = optional($parent_aip_request)->id;
        }


        if (isset($final_submission_to_tetfund->data) && $final_submission_to_tetfund->data != null) {
            $response = $final_submission_to_tetfund->data;

            //update submission request record status
            $submissionRequest->status = 'submitted';
            $submissionRequest->tf_iterum_portal_key_id = $response->id;
            $submissionRequest->tf_iterum_portal_request_status = $response->request_status;
            $submissionRequest->tf_iterum_portal_response_meta_data = json_encode($response);
            $submissionRequest->tf_iterum_portal_response_at = date('Y-m-d H:i:s');
            $submissionRequest->save();

            // execute for ASTD Interventions only
            if ($submissionRequest->is_astd_intervention(optional($request)->intervention_name)==true) {

                //update attached final_nominations_arr
                NominationRequest::where('bi_submission_request_id', null)
                    ->where('beneficiary_id', $beneficiary_member->beneficiary_id)
                    ->whereIn('id',$final_nominations_arr->pluck('id'))
                    ->where('type', $intervention_name)
                    ->where('head_of_institution_checked_status', 'approved')
                    ->update([
                        'bi_submission_request_id' => $submissionRequest->id,
                        'is_set_for_final_submission' => 1,
                    ]);
            }

            $success_message = "This Request Has Now Been Successfully Submitted To TETFund!!";
            return redirect()->back()->with('success', $success_message);
        }

        return redirect()->back()->withErrors(['Oops!!!, A Server Error was encountered while processing final submission.']);

    }

    // generate checklist name
    public function generateCheckListGroupName($intervention_line_name, $submissionRequest, $is_audit_checklist=false) {

        if ($submissionRequest->is_aip_request == true)  {
            $checklist_group_name = $intervention_line_name.' - AIPCheckList';
            if(strtolower($submissionRequest->type) == 'request for fund') {
                $checklist_group_name = $intervention_line_name.' - FundRequestCheckList';
            }
        } elseif ($submissionRequest->is_first_tranche_request == true) {
            $checklist_group_name = $intervention_line_name.' - FirstTranchePaymentCheckList';
        } elseif ($submissionRequest->is_second_tranche_request == true && $is_audit_checklist==false) {
            $checklist_group_name = $intervention_line_name.' - SecondTranchePaymentCheckList';
        } elseif ($submissionRequest->is_second_tranche_request == true && $is_audit_checklist==true) {
            $checklist_group_name = $intervention_line_name.' - AuditClearanceSecondTranchePaymentCheckList';
        } elseif ($submissionRequest->is_final_tranche_request == true && $is_audit_checklist==false) {
            $checklist_group_name = $intervention_line_name.' - FinalPaymentCheckList';
        } elseif ($submissionRequest->is_final_tranche_request == true && $is_audit_checklist==true) {
            $checklist_group_name = $intervention_line_name.' - AuditClearanceFinalPaymentCheckList';
        } elseif ($submissionRequest->is_monitoring_request == true) {
            $checklist_group_name = $intervention_line_name.' - MonitoringEvaluationCheckList';
        }

        return str_replace(' ', '_', $checklist_group_name ?? '0');
    }

    // show details for monitoring request
    public function showMonitoring(Organization $ord, Request $request, $id) {
        $monitoring_request = SubmissionRequest::where(function($query) use ($id) {
                            $query->where('id', $id)
                                  ->orWhere('tf_iterum_portal_key_id', $id);
                        })
                        ->where('is_monitoring_request', true)
                        ->first();
        
        if (empty($monitoring_request)) {
            return redirect(route('tf-bi-portal.monitoring'))->with('error', 'The Monitoring Request Was Not Found!');
        }

        if($monitoring_request->status != 'not-submitted') {
            // server class constructor
            $tetFundServer = new TETFundServer();   
            $monitoring_request_submitted = $tetFundServer->getMonitoringRequestData($monitoring_request->tf_iterum_portal_key_id);

            // changing monitoring request status to Approved (is_approved is true)
            if ($monitoring_request_submitted->is_approved && $monitoring_request->status != 'approved') {
                $monitoring_request->status = 'approved';
                $monitoring_request->save();
            } elseif($monitoring_request_submitted->is_approved==false && $monitoring_request->status=='approved') {
                $monitoring_request->status = 'submitted';
                $monitoring_request->save();
            }
        }

        $current_user = auth()->user();
        $beneficiary = $monitoring_request->beneficiary; // beneficiary
        $submission_request = $monitoring_request->find($monitoring_request->parent_id); // submission request

        // get all interventions from server
        $pay_load = ['_method'=>'GET', 'id'=>$monitoring_request->tf_iterum_intervention_line_key_id];
        $tetFundServer = new TETFundServer();   /* server class constructor */
        $intervention_types_server_response = $tetFundServer->get_row_records_from_server("tetfund-ben-mgt-api/interventions/".$monitoring_request->tf_iterum_intervention_line_key_id, $pay_load);

        return view('tf-bi-portal::pages.monitoring.show')
                ->with('current_user', $current_user)
                ->with('monitoring_request', $monitoring_request)
                ->with('submission_request', $submission_request)
                ->with('intervention', $intervention_types_server_response)
                ->with('monitoring_request_submitted', $monitoring_request_submitted ?? []);
    }
    


    // returns PI applicable request type
    public function getAppplicableRequestType($submissionRequest, $intrvention_name) {
        $returned_arr = [];

        if (str_contains(strtolower($intrvention_name), "physical infrastructure") || str_contains(strtolower($intrvention_name), "zonal intervention")) {

            $has_procurement_artifact = $submissionRequest->artifact('has_procurement');
            $has_construction_artifact = $submissionRequest->artifact('has_construction');

            $returned_arr['has_procurement'] = empty($has_procurement_artifact) || optional($has_procurement_artifact)->value=='true' ? true : false;
            $returned_arr['has_construction'] = empty($has_construction_artifact) || optional($has_construction_artifact)->value=='true' ? true : false;
        }

        return $returned_arr;
    }



    // show details for submission request
    public function show(Organization $org, Request $request, $id) {
        /** @var SubmissionRequest $submissionRequest */
        
        $submissionRequest = SubmissionRequest::where('id', $id)
                                ->orWhere('tf_iterum_portal_key_id', $id)
                                ->first();

        if (empty($submissionRequest)) {
            //Flash::error('Submission Request not found');
            return redirect(route('tf-bi-portal.submissionRequests.index'));
        }

        $beneficiary = $submissionRequest->beneficiary; // beneficiary

        // tracking Iterum records if submission has been completed to tetfund
        if($submissionRequest->status != 'not-submitted') {
            // checklist group surfix name
            $checklist_group_name_surfix = self::generateCheckListGroupName('', $submissionRequest);
            
            // server class constructor
            $tetFundServer = new TETFundServer();   
            $submitted_request_data = $tetFundServer->getSubmissionRequestData($submissionRequest->tf_iterum_portal_key_id, $checklist_group_name_surfix);

            $checklist_items = (array)$submitted_request_data->checklist_items;
      
            $bi_request_released_communications = $submitted_request_data->releasedBICommunication;

            if($submitted_request_data->request_status=='recalled') {
                // change submision request status  to not-submitted
                $submissionRequest->status='not-submitted';

                // set intervention years to currently changed
                $years = $submissionRequest->getInterventionYears();

                // get all interventions from server
                $pay_load = ['_method'=>'GET', 'id'=>$submissionRequest->tf_iterum_intervention_line_key_id];
                $tetFundServer = new TETFundServer();   /* server class constructor */
                $intervention_types_server_response = $tetFundServer->get_row_records_from_server("tetfund-ben-mgt-api/interventions/".$submissionRequest->tf_iterum_intervention_line_key_id, $pay_load);

                // decide fund availability type to be retrived is ASTD based
                if ($submissionRequest->is_astd_intervention(optional($intervention_types_server_response)->name)==true) {
                    $data_to_rerieve_payload['getASTDFundAvailabilityData'] = [
                            'beneficiary_id' => $beneficiary->tf_iterum_portal_key_id,
                            'intervention_name' => optional($intervention_types_server_response)->name,
                            'intervention_line_id' => $submissionRequest->tf_iterum_intervention_line_key_id,
                            'allocation_details'=>true
                        ];                    

                    $tetFundServer = new TETFundServer();   /* server class constructor */
                    $some_server_data_array = $tetFundServer->getSomeDataArrayFromServer($data_to_rerieve_payload);
                    //Get the ASTD funding data and total_funds.
                    $submission_allocations = $some_server_data_array->getASTDFundAvailabilityData ?? null;


                // decide fund availability type to be retrived is First Tranch based
                } elseif ($submissionRequest->is_first_tranche_request && $submissionRequest->is_start_up_first_tranche_intervention($intervention_types_server_response->intervention->name ?? $intervention_types_server_response->name ?? '')) {
                    $data_to_rerieve_payload['getFirstTranchBasedFundAvailabilityData'] = [
                            'beneficiary_id' => $beneficiary->tf_iterum_portal_key_id,
                            'intervention_name' => optional($intervention_types_server_response)->name,
                            'intervention_line_id' => $submissionRequest->tf_iterum_intervention_line_key_id,
                            'allocation_details'=>true
                        ];                    

                    $tetFundServer = new TETFundServer();   /* server class constructor */
                    $some_server_data_array = $tetFundServer->getSomeDataArrayFromServer($data_to_rerieve_payload);
                    
                    //Get the first tranche based funding data and total_funds.
                    $submission_allocations = $some_server_data_array->getFirstTranchBasedFundAvailabilityData ?? null;

                } else {
                    // get fund availability
                    $tetFundServer = new TETFundServer();   /* server class constructor */
                    $submission_allocations = $tetFundServer->getFundAvailabilityData($submitted_request_data->beneficiary_id, $submissionRequest->tf_iterum_intervention_line_key_id, $years, true);
                }
            } else {
                // changing submission status to Approved
                if (($submitted_request_data->has_generated_aip || $submitted_request_data->has_generated_disbursement_memo) && $submissionRequest->status != 'approved') {
                    $submissionRequest->status = 'approved';
                    $submissionRequest->save();
                } elseif($submitted_request_data->has_generated_aip==false && $submitted_request_data->has_generated_disbursement_memo==false && $submissionRequest->status == 'approved') {
                    $submissionRequest->status = 'submitted';
                    $submissionRequest->save();
                }

                $years = $submitted_request_data->years;
                $submission_allocations = $submitted_request_data->submission_allocations;
                $intervention_types_server_response = $submitted_request_data->intervention_beneficiary_type;
            }

        } else {

            // get all interventions from server
            $pay_load = ['_method'=>'GET', 'id'=>$submissionRequest->tf_iterum_intervention_line_key_id];
            $tetFundServer = new TETFundServer();   /* server class constructor */
            $intervention_types_server_response = $tetFundServer->get_row_records_from_server("tetfund-ben-mgt-api/interventions/".$submissionRequest->tf_iterum_intervention_line_key_id, $pay_load);

            // intervention checklist group name
            $checklist_group_name = self::generateCheckListGroupName($intervention_types_server_response->name ?? '', $submissionRequest);

            // get audit checklist for tranches applicable
            $additional_checklists_payload = ['_method' => 'POST'];
            if ($submissionRequest->is_second_tranche_request || $submissionRequest->is_final_tranche_request) {
                $additional_checklists_payload['checklist_group_name_audit'] = self::generateCheckListGroupName($intervention_types_server_response->name??'', $submissionRequest, true);
            }
            
            $years = $submissionRequest->getInterventionYears();

            // get some array of data from server
            $data_to_rerieve_payload = [
                'getInterventionChecklistData' => [
                    'applicable_years' => $years,
                    'request' => $additional_checklists_payload,
                    'checklist_group_name' => $checklist_group_name
                ]
            ];
            

            // applicable request type
            $lower_case_intervention_name = strtolower($intervention_types_server_response->name??'');
            if ($submissionRequest->is_aip_request && (str_contains($lower_case_intervention_name, "physical infrastructure") || str_contains($lower_case_intervention_name, "zonal intervention")) && in_array(2023,$years)) {

                $applicable_request_types = $this->getAppplicableRequestType($submissionRequest, $intervention_types_server_response->name??'');

                $data_to_rerieve_payload['getInterventionChecklistData']['has_procurement'] = $applicable_request_types['has_procurement'];
                $data_to_rerieve_payload['getInterventionChecklistData']['has_construction'] = $applicable_request_types['has_construction'];
            }


            // decide fund availability type to be retrived
            $is_astd_intervention = false;
            $is_first_tranche_intervention = false;
            if ($submissionRequest->is_astd_intervention(optional($intervention_types_server_response)->name)==true) {
                $is_astd_intervention = true;
                $data_to_rerieve_payload['getASTDFundAvailabilityData'] = [
                    'beneficiary_id' => $beneficiary->tf_iterum_portal_key_id,
                    'intervention_name' => optional($intervention_types_server_response)->name,
                    'intervention_line_id' => $submissionRequest->tf_iterum_intervention_line_key_id,
                    'allocation_details'=>true
                ];

            } elseif ($submissionRequest->is_first_tranche_request && $submissionRequest->is_start_up_first_tranche_intervention($intervention_types_server_response->intervention->name ?? $intervention_types_server_response->name ?? '')) {

                $is_first_tranche_intervention = true;
                $data_to_rerieve_payload['getFirstTranchBasedFundAvailabilityData'] = [
                    'beneficiary_id' => $beneficiary->tf_iterum_portal_key_id,
                    'intervention_name' => optional($intervention_types_server_response)->name,
                    'intervention_line_id' => $submissionRequest->tf_iterum_intervention_line_key_id,
                    'allocation_details'=>true
                ];

            } else {
                $data_to_rerieve_payload['getFundAvailabilityData'] = [
                        'beneficiary_id' => $beneficiary->tf_iterum_portal_key_id,
                        'years' => $years,
                        'tf_iterum_intervention_line_key_id' => $submissionRequest->tf_iterum_intervention_line_key_id,
                        'allocation_details'=>true
                    ];
            }

            $tetFundServer = new TETFundServer();   /* server class constructor */
            $some_server_data_array = $tetFundServer->getSomeDataArrayFromServer($data_to_rerieve_payload);

            // get checklist for specified intervention
            $checklist_items = (array)$some_server_data_array->getInterventionChecklistData??[];

            //Get the funding data and total_funds for the selected intervention year(s) if non-astd.
            $submission_allocations = $some_server_data_array->getFundAvailabilityData??null;
            if ($is_astd_intervention==true) {
                $submission_allocations = $some_server_data_array->getASTDFundAvailabilityData??null;
            } elseif ($is_first_tranche_intervention==true) {
                $submission_allocations = $some_server_data_array->getFirstTranchBasedFundAvailabilityData??null;
            }
            
        }

        if(isset($request->sub_menu_items) && $request->sub_menu_items == 'nominations_binded') {
            if(!empty($intervention_types_server_response) && $intervention_types_server_response != null) {
                if (str_contains(strtolower($intervention_types_server_response->name), 'teaching practice')) {
                    $intervention_name = 'tp';
                } elseif (str_contains(strtolower($intervention_types_server_response->name), 'conference attendance')) {
                    $intervention_name = 'ca';
                } elseif (str_contains(strtolower($intervention_types_server_response->name), 'tetfund scholarship')) {
                    $intervention_name = 'tsas';
                }
            }

            $binded_nominations_dataTable = new BindedNominationsDataTable($org);
            return $binded_nominations_dataTable
                ->with('user_beneficiary', $beneficiary)
                ->with('submission_request', $submissionRequest)
                ->with('parentAIPSubmissionRequest', $submissionRequest->is_aip_request || 
                    ($submissionRequest->is_first_tranche_request && $submissionRequest->is_start_up_first_tranche_intervention($intervention_types_server_response->intervention->name ?? $intervention_types_server_response->name ?? '')) ? 
                    $submissionRequest : 
                    $submissionRequest->getParentAIPSubmissionRequest())
                ->with('firstTrancheSubmissionRequest', $submissionRequest->is_first_tranche_request ? $submissionRequest : $submissionRequest->getFirstTrancheSubmissionRequest())
                ->with('secondTrancheSubmissionRequest', $submissionRequest->is_second_tranche_request ? $submissionRequest : $submissionRequest->getSecondTrancheSubmissionRequest())
                ->with('finalTrancheSubmissionRequest', $submissionRequest->is_final_tranche_request ? $submissionRequest : $submissionRequest->getFinalTrancheSubmissionRequest())
                ->with('allSubmissionAttachments', $submissionRequest->get_all_attachments($submissionRequest->id))
                ->with('intervention_name', $intervention_name ?? null)
                ->render('pages.submission_requests.show', [
                    'intervention' => $intervention_types_server_response->intervention ?? $intervention_types_server_response,
                    'submissionRequest' => $submissionRequest,
                    'years' => $years,
                    'checklist_items' => $checklist_items,
                    'allocations_details' => $submission_allocations,
                    'fund_available' => $submission_allocations->total_funds ?? $submission_allocations->total_available_fund ?? 0,
                    'submission_allocations' => $submission_allocations->allocation_records ?? [],
                    'beneficiary' => $beneficiary, 
                    'submitted_request_data' => $submitted_request_data ?? []
                ]);
        }

        // retriveing related checklist records for PI intevention at AIP stages
        $applicable_request_types = $this->getAppplicableRequestType($submissionRequest, $intervention_types_server_response->name??'');

        $has_procurement_flag = $applicable_request_types['has_procurement']??false;
        $has_construction_flag = $applicable_request_types['has_construction']??false;


        return view('pages.submission_requests.show')
            ->with('intervention', $intervention_types_server_response->intervention ?? $intervention_types_server_response)
            ->with('submissionRequest', $submissionRequest)
            ->with('has_procurement_flag', $has_procurement_flag)
            ->with('has_construction_flag', $has_construction_flag)
            ->with('parentAIPSubmissionRequest', $submissionRequest->is_aip_request || 
                    ($submissionRequest->is_first_tranche_request && $submissionRequest->is_start_up_first_tranche_intervention($intervention_types_server_response->intervention->name ?? $intervention_types_server_response->name ?? '')) ? 
                    $submissionRequest : 
                    $submissionRequest->getParentAIPSubmissionRequest())
            ->with('firstTrancheSubmissionRequest', $submissionRequest->is_first_tranche_request ? $submissionRequest : $submissionRequest->getFirstTrancheSubmissionRequest())
            ->with('secondTrancheSubmissionRequest', $submissionRequest->is_second_tranche_request ? $submissionRequest : $submissionRequest->getSecondTrancheSubmissionRequest())
            ->with('finalTrancheSubmissionRequest', $submissionRequest->is_final_tranche_request ? $submissionRequest : $submissionRequest->getFinalTrancheSubmissionRequest())
            ->with('allSubmissionAttachments', $submissionRequest->get_all_attachments($submissionRequest->id))
            ->with('years', $years)
            ->with('checklist_items', $checklist_items)
            ->with('allocations_details', $submission_allocations)
            ->with('fund_available', $submission_allocations->total_funds??$submission_allocations->total_available_fund??0)
            ->with('submission_allocations', $submission_allocations->allocation_records ?? [])
            ->with('bi_request_released_communications', $bi_request_released_communications ?? [])
            ->with('beneficiary', $beneficiary)
            ->with('submitted_request_data', $submitted_request_data ?? []);
    }

    /**
     * Show the form for editing the specified SubmissionRequest.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit(Organization $org, $id) {
        /** @var SubmissionRequest $submissionRequest */
        $submissionRequest = SubmissionRequest::find($id);

        if (empty($submissionRequest)) {
            //Flash::error('Submission Request not found');
            return redirect(route('tf-bi-portal.submissionRequests.index'));
        }

        $supposed_intervention_name = explode('-', $submissionRequest->title);
        if (($submissionRequest->status=='not-submitted' || $submissionRequest->status=='recalled') && ($submissionRequest->is_aip_request==true || ($submissionRequest->is_first_tranche_request==true && $submissionRequest->is_start_up_first_tranche_intervention(trim($supposed_intervention_name[0]))))) {

            $current_user = auth()->user();
            $current_user_roles = $current_user->roles->pluck('name')->toArray();
            $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();
            
            // setting title prefix
            $title_spilt = explode('-', $submissionRequest->title);
            $submissionRequest->title = !empty($submissionRequest->title) && count($title_spilt)==2 ? trim($title_spilt[0]) : '';

            // checking if user has the functionality to edit this request
            if (!($submissionRequest->can_user_operate_intervention($current_user_roles, $submissionRequest->title))) {
                return redirect()->back()->withError('User does not have permission to edit the Submission Request!')->withInput();
            }

            $pay_load = [   
                '_method' => 'GET',
                'beneficiary_type' => $beneficiary_member->beneficiary->type ?? null,
                'interventions_to_skip' => $submissionRequest->interventions_denied_submission()
            ];

            $tetFundServer = new TETFundServer();   /* server class constructor */
            $intervention_types_server_response = $tetFundServer->get_all_data_list_from_server('tetfund-ben-mgt-api/interventions', $pay_load);

            $years = [];
            for ($i=0; $i < 6; $i++) { 
              array_push($years, date("Y")-$i);
            }

            if (count($intervention_types_server_response) > 0) {
                foreach ($intervention_types_server_response as $key => $value) {
                    if ($value->id == optional($submissionRequest)->tf_iterum_intervention_line_key_id) {
                        $selected_intervention_line = $value;
                        break;
                    }
                }
            }


            return view('pages.submission_requests.edit')
                ->with('submissionRequest', $submissionRequest)
                ->with('selected_intervention_line', $selected_intervention_line ?? [])
                ->with("years", $years)
                ->with("intervention_types", $intervention_types_server_response);
        }
        
        return redirect(route('tf-bi-portal.submissionRequests.index'));
    }

    /**
     * Update the specified SubmissionRequest in storage.
     *
     * @param  int              $id
     * @param UpdateSubmissionRequestRequest $request
     *
     * @return Response
     */
    public function update(Organization $org, $id, UpdateSubmissionRequestRequest $request) {
        /** @var SubmissionRequest $submissionRequest */
        $submissionRequest = SubmissionRequest::find($id);

        if (empty($submissionRequest)) {
            return redirect(route('tf-bi-portal.submissionRequests.index'))->withError('Submission Request not found!');
        }

        if ($submissionRequest->status=='recalled'){
         // server class constructor
            $tetFundServer = new TETFundServer();   
            $submitted_request_data = $tetFundServer->getSubmissionRequestData($submissionRequest->tf_iterum_portal_key_id);
        }

        $current_user = auth()->user();
        $current_user_roles = $current_user->roles->pluck('name')->toArray();
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();
        
        // checking if current user has the permission to process this update
        if (!($submissionRequest->can_user_operate_intervention($current_user_roles, $request->get('intervention_name')))) {
            return redirect()->back()->withError('User does not have permission to modify the Submission Request!')->withInput();
        }


        $old_intervention_name = trim(explode('-', $submissionRequest->title)[0]??'');
        
        if (($submissionRequest->status=='not-submitted' || $submitted_request_data->request_status??''=='recalled') && ($submissionRequest->is_aip_request==true || ($submissionRequest->is_first_tranche_request==true && ($submissionRequest->is_start_up_first_tranche_intervention($request->intervention_name) || $submissionRequest->is_start_up_first_tranche_intervention($old_intervention_name))))) {

            $input = $request->all();
            $input['intervention_year1'] = 0;
            $input['intervention_year2'] = 0;
            $input['intervention_year3'] = 0;
            $input['intervention_year4'] = 0;

            $years = [];
            if ($request->intervention_year1 != null) {
                array_push($years, $request->intervention_year1);
            }

            if ($request->intervention_year2 != null) {
                array_push($years, $request->intervention_year2);
            }

            if ($request->intervention_year3 != null) {
                array_push($years, $request->intervention_year3);
            }

            if ($request->intervention_year4 != null) {
                array_push($years, $request->intervention_year4);
            }
            $years_unique = array_unique($years);
            sort($years_unique);
            
            $counter = 1;
            foreach($years_unique as $year) {
                $input['intervention_year'.$counter] = $year;
                $counter += 1;
            }
            
            $input['requesting_user_id'] = $current_user->id;
            // check if a similar request does exit
            if (!SubmissionRequest::is_astd_intervention($request->intervention_name) && !SubmissionRequest::is_start_up_first_tranche_intervention($request->intervention_name) && $beneficiary_member->beneficiary->hasRequest($request->tf_iterum_intervention_line_key_id, $input['intervention_year1'], $input['intervention_year2'], $input['intervention_year3'], $input['intervention_year4'], $submissionRequest->id, $request->intervention_name)) {
                $error_msg = "A previous submission request for one or more of the selected years has already been submitted.";
                return redirect()->back()->withErrors([$error_msg])->withInput();
            }

            // set approprate intervention tranche & type as it applies to the selected intervention name
            if ($submissionRequest->is_aip_request==true && $submissionRequest->is_start_up_first_tranche_intervention($request->intervention_name)) {
                $input['is_aip_request'] = false;
                $input['is_first_tranche_request'] = true;
                $input['type'] = '1st Tranche Payment';
            } elseif ($submissionRequest->is_first_tranche_request==true && !$submissionRequest->is_start_up_first_tranche_intervention($request->intervention_name)) {
                $input['is_aip_request'] = true;
                $input['type'] = 'Request for AIP';
                $input['is_first_tranche_request'] = false;
            }

            $type_surfix = $submissionRequest->type;
            if (SubmissionRequest::is_astd_intervention($request->intervention_name) == true) {
                $type_surfix = 'Request For Fund';
            }

            $input['title'] = $input['intervention_name']. ' - ' .$type_surfix. ' (' .implode(', ', $years_unique) .')';

            $submissionRequest->fill($input);
            
            $submissionRequest->save();
            
            SubmissionRequestUpdated::dispatch($submissionRequest);
            return redirect(route('tf-bi-portal.submissionRequests.show', $submissionRequest->id))->with('success', 'Submission Request updated successfully.')->with('submissionRequest', $submissionRequest);
        }
        dd('outside');
        return redirect(route('tf-bi-portal.submissionRequests.index'));
    }

    /**
     * Remove the specified SubmissionRequest from storage.
     *
     * @param  int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Organization $org, $id) {
        /** @var SubmissionRequest $submissionRequest */
        $submissionRequest = SubmissionRequest::find($id);

        if (empty($submissionRequest)) {
            //Flash::error('Submission Request not found');

            return redirect(route('tf-bi-portal.submissionRequests.index'));
        }

        $submissionRequest->delete();

        //Flash::success('Submission Request deleted successfully.');
        SubmissionRequestDeleted::dispatch($submissionRequest);
        return redirect(route('tf-bi-portal.submissionRequests.index'));
    }

     
}
