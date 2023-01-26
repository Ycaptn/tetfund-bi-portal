<?php

namespace App\Http\Controllers\Models;

use App\Models\SubmissionRequest;
use App\Models\NominationRequest;

use App\Events\SubmissionRequestCreated;
use App\Events\SubmissionRequestUpdated;
use App\Events\SubmissionRequestDeleted;

use App\Http\Requests\CreateSubmissionRequestRequest;
use App\Http\Requests\UpdateSubmissionRequestRequest;
use App\Http\Requests\ProcessAttachmentsSubmissionRequest;

use App\DataTables\SubmissionRequestDataTable;
use App\DataTables\BindedNominationsDataTable;

use Hasob\FoundationCore\Controllers\BaseController;
use Hasob\FoundationCore\Models\Organization;
use Hasob\FoundationCore\View\Components\CardDataView;

use Flash;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Managers\TETFundServer;
use App\Models\BeneficiaryMember;


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

        $cdv_submission_requests = new CardDataView(SubmissionRequest::class, "pages.submission_requests.card_view_item");
        $cdv_submission_requests->setDataQuery(['organization_id'=>$org->id, 'beneficiary_id'=>optional($beneficiary_member)->beneficiary_id])
                        ->addDataGroup('All','deleted_at',null)
                        ->addDataGroup('Not Submitted','status','not-submitted')
                        ->addDataGroup('In Progress','status','in-progress')
                        ->addDataGroup('Approved','status','aip')
                        ->addDataGroup('Recalled','status','recall')
                        ->enableSearch(true)
                        ->addDataOrder('created_at', 'DESC')
                        ->enablePagination(true)
                        ->setPaginationLimit(20)
                        ->setSearchPlaceholder('Search Submissions by Project Title');

        if (request()->expectsJson()){
            return $cdv_submission_requests->render();
        }

        return view('pages.submission_requests.card_view_index')
                    ->with('current_user', $current_user)
                    ->with('months_list', BaseController::monthsList())
                    ->with('states_list', BaseController::statesList())
                    ->with('cdv_submission_requests', $cdv_submission_requests);

    }

    /**
     * Show the form for creating a new SubmissionRequest.
     *
     * @return Response
     */
    public function create(Organization $org) {
        $pay_load = ['_method'=>'GET'];
        $tETFundServer = new TETFundServer();   /* server class constructor */
        $intervention_types_server_response = $tETFundServer->get_all_data_list_from_server('tetfund-ben-mgt-api/interventions', $pay_load);

          $years = [];
          for ($i=0; $i < 6; $i++) { 
              array_push($years, date("Y")-$i);
          }

        return view('pages.submission_requests.create')
            ->with("type", 'AIP')
            ->with("years", $years)
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
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();
        
        $input['type'] = 'intervention';
        $input['status'] = 'not-submitted';
        $input['requesting_user_id'] = $current_user->id;
        $input['organization_id'] = $current_user->organization_id;
        $input['beneficiary_id'] = $beneficiary_member->beneficiary_id;

        /** @var SubmissionRequest $submissionRequest */
        $submissionRequest = SubmissionRequest::create($input);

        /*Dispatch event*/
        SubmissionRequestCreated::dispatch($submissionRequest);
        return redirect(route('tf-bi-portal.submissionRequests.show', $submissionRequest->id))->with('success', 'Submission Request saved successfully.')->with('submissionRequest', $submissionRequest);
    }

    /* implement processing success */
    public function processSubmissionRequestAttachment(ProcessAttachmentsSubmissionRequest $request, $id) {
        $attachement_inputs = $request->all();
        $submissionRequest = SubmissionRequest::find($request->id);

        //get existing checklist for this intervention
        $checklist_items_arr = array();
        $tETFundServer = new TETFundServer();   /* server class constructor */
        $checklist_items = $tETFundServer->getInterventionChecklistData($request->intervention_line,);
        
        //mapping checklist id to item_label 
        foreach ($checklist_items as $checklist){
            $checklist_items_arr[$checklist->id] = $checklist->item_label;
        }

        //processing individual checklist file uploads
        if($request->checklist_input_fields != "") {
            $checklist_input_fields_arr = explode(',', $request->checklist_input_fields);
            foreach ($checklist_input_fields_arr as $checklist_input_name) {
                if (isset($attachement_inputs[$checklist_input_name]) && $request->hasFile($checklist_input_name)) {
                    $checklist_id = substr("$checklist_input_name",10);
                    $label = $checklist_items_arr[$checklist_id]; 
                    $discription = 'This Document Contains the ' . $label ;
                    $submissionRequest->attach(auth()->user(), $label, $discription, $attachement_inputs[$checklist_input_name]);
                }
            }
        }

        //handling additional files submission
        if (isset($request->additional_attachment) && $request->hasFile('additional_attachment')) {
            $label = $request->additional_attachment_name . ' Additional Attachment'; 
            $discription = 'This Document Contains the ' . $label ;
            $submissionRequest->attach(auth()->user(), $label, $discription, $attachement_inputs['additional_attachment']);
        }   

        $success_message = 'Submission Request Attachments saved successfully!';
        return redirect()->back()->with('success', $success_message);
    
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

        if($current_user->hasRole('BI-desk-officer') == false) {
            return redirect()->back()->with('error', 'Please, Kindly Contact the Institution TETFund Desk Officer, as only them has the Privilege to Submit This Request');
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

        //get total fund available 
        $tETFundServer = new TETFundServer();   /* server class constructor */
        $fund_availability = $tETFundServer->getFundAvailabilityData($beneficiary->tf_iterum_portal_key_id, $submissionRequest->tf_iterum_intervention_line_key_id, $years, true);
        
        //error when no fund allocation for selected year(s) is found
        if (isset($fund_availability->success) && $fund_availability->success == false && $fund_availability->message != null) {
            array_push($errors_array, $fund_availability->message);
        }
        

        if (isset($fund_availability->total_funds) && $fund_availability->total_funds != $submissionRequest->amount_requested && (!str_contains(strtolower(optional($request)->intervention_name), 'teaching practice') && !str_contains(strtolower(optional($request)->intervention_name), 'conference attendance') && !str_contains(strtolower(optional($request)->intervention_name), 'tetfund scholarship')) ) {
           
            //error for requested fund mismatched to allocated fund non-astd interventions
            array_push($errors_array, "Fund requested must be equal to the Allocated amount.");
       
        } else if (isset($fund_availability->total_funds) && $submissionRequest->amount_requested > $fund_availability->total_funds && (str_contains(strtolower(optional($request)->intervention_name), 'teaching practice') || str_contains(strtolower(optional($request)->intervention_name), 'conference attendance') || str_contains(strtolower(optional($request)->intervention_name), 'tetfund scholarship')) ) {
            
            //error for requested fund mismatched to allocated fund for all ASTD interventions
            array_push($errors_array, "Fund requested cannot be greater than allocated amount.");
        }

        //error when at least one selected allocation year is found
        if (isset($fund_availability->allocation_records) && count($fund_availability->allocation_records) > 0) {
            $all_valid_allocation_year = array_column($fund_availability->allocation_records, 'year');
            foreach($years as $year) {
                if (!in_array($year, $all_valid_allocation_year)) {
                    array_push($errors_array, "No allocation datails is found for selected Intervention year ". $year);
                }
            }
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
        $pay_load = $submissionRequest->toArray();
        $pay_load['tf_beneficiary_id'] = $tf_beneficiary_id;
        $pay_load['_method'] = 'POST';
        $pay_load['is_aip_request'] = true;
        $pay_load['requested_tranche'] = 'AIP';
        $pay_load['submission_user'] = $current_user;

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
        if (str_contains(strtolower(optional($request)->intervention_name), 'teaching practice') || 
            str_contains(strtolower(optional($request)->intervention_name), 'conference attendance') || 
            str_contains(strtolower(optional($request)->intervention_name), 'tetfund scholarship') ) {

            $final_nominations_arr = NominationRequest::with($nomination_table)
                    ->with('attachables.attachment')
                    ->where('bi_submission_request_id', null)
                    ->where('beneficiary_id', $beneficiary_member->beneficiary_id)
                    ->where('type', $intervention_name)
                    ->where('head_of_institution_checked_status', 'approved')
                    ->get();
            $pay_load['final_nominations_arr'] = $final_nominations_arr;
            $pay_load['nomination_table'] = $nomination_table;
        }

        $tETFundServer = new TETFundServer();   /* server class constructor */
        $final_submission_to_tetfund = $tETFundServer->processSubmissionRequest($pay_load, $tf_beneficiary_id);

        if (isset($final_submission_to_tetfund->data) && $final_submission_to_tetfund->data != null) {
            $response = $final_submission_to_tetfund->data;

            //update submission request record status
            $submissionRequest->status = 'submitted';
            $submissionRequest->tf_iterum_portal_key_id = $response->id;
            $submissionRequest->tf_iterum_portal_request_status = $response->request_status;
            $submissionRequest->tf_iterum_portal_response_meta_data = json_encode($response);
            $submissionRequest->tf_iterum_portal_response_at = date('Y-m-d');
            $submissionRequest->save();

            // execute for ASTD Interventions only
            if (str_contains(strtolower(optional($request)->intervention_name), 'teaching practice') || 
                str_contains(strtolower(optional($request)->intervention_name), 'conference attendance') || 
                str_contains(strtolower(optional($request)->intervention_name), 'tetfund scholarship') ) {

                //update attached final_nominations_arr
                NominationRequest::where('bi_submission_request_id', null)
                    ->where('beneficiary_id', $beneficiary_member->beneficiary_id)
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

        return redirect()->back()->withErrors(['Oops!!!, An unknown error was encountered while processing final submission.']);

    }

    /**
     * Display the specified SubmissionRequest.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show(Organization $org, $id, Request $request, BindedNominationsDataTable $binded_nominations_dataTable) {
        /** @var SubmissionRequest $submissionRequest */
        $submissionRequest = SubmissionRequest::find($id);

        if (empty($submissionRequest)) {
            //Flash::error('Submission Request not found');
            return redirect(route('tf-bi-portal.submissionRequests.index'));
        }
            
        $beneficiary = $submissionRequest->beneficiary; // beneficiary

        // tracking Iterum records if submission has been completed to tetfund
        if($submissionRequest->status != 'not-submitted') {
            
            $tETFundServer = new TETFundServer();   // server class constructor
            $submitted_request_data = $tETFundServer->getSubmittedRequestData($submissionRequest->tf_iterum_portal_key_id);
            
            $checklist_items = $submitted_request_data->checklist_items;
            $intervention_types_server_response = $submitted_request_data->intervention_beneficiary_type;
            $submission_allocations = $submitted_request_data->submission_allocations;
            $years = $submitted_request_data->years;

        } else {

            /* get checklist binded to specified intervention model artifact */
            $tETFundServer = new TETFundServer();   /* server class constructor */
            $checklist_items = $tETFundServer->getInterventionChecklistData($submissionRequest->tf_iterum_intervention_line_key_id);

            /* get all interventions from server */
            $pay_load = ['_method'=>'GET', 'id'=>$submissionRequest->tf_iterum_intervention_line_key_id];
            $tETFundServer = new TETFundServer();   /* server class constructor */
            $intervention_types_server_response = $tETFundServer->get_row_records_from_server("tetfund-ben-mgt-api/interventions/".$submissionRequest->tf_iterum_intervention_line_key_id, $pay_load);
            
            $years = array();
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

            //Get the funding data and total_funds for the selected intervention year(s).
            $tETFundServer = new TETFundServer();   /* server class constructor */
            $submission_allocations = $tETFundServer->getFundAvailabilityData($beneficiary->tf_iterum_portal_key_id, $submissionRequest->tf_iterum_intervention_line_key_id, $years, true);
        }

        $intervention_name = '';
        if (str_contains(strtolower($intervention_types_server_response->name), 'teaching practice')) {
            $intervention_name = 'tp';
        } elseif (str_contains(strtolower($intervention_types_server_response->name), 'conference attendance')) {
            $intervention_name = 'ca';
        } elseif (str_contains(strtolower($intervention_types_server_response->name), 'tetfund scholarship')) {
            $intervention_name = 'tsas';
        }

        if(isset($request->sub_menu_items) && $request->sub_menu_items == 'nominations_binded') {
             return $binded_nominations_dataTable
                    ->with('user_beneficiary', $beneficiary)
                    ->with('submission_request', $submissionRequest)
                    ->with('intervention_name', $intervention_name)
                    ->render('pages.submission_requests.show', [
                        'intervention' => $intervention_types_server_response,
                        'submissionRequest' => $submissionRequest,
                        'years' => $years,
                        'checklist_items' => $checklist_items,
                        'fund_available' => optional($submission_allocations)->total_funds,
                        'submission_allocations' => optional($submission_allocations)->allocation_records,
                        'beneficiary' => $beneficiary, 
                        'submitted_request_data' => $submitted_request_data ?? []
                    ]);
        }

        return view('pages.submission_requests.show')
            ->with('intervention', $intervention_types_server_response)
            ->with('submissionRequest', $submissionRequest)
            ->with('years', $years)
            ->with('checklist_items', $checklist_items)
            ->with('fund_available', optional($submission_allocations)->total_funds)
            ->with('submission_allocations', optional($submission_allocations)->allocation_records)
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
            return redirect(route('tf-bi-submission.submissionRequests.index'));
        }

        if ($submissionRequest->status == 'not-submitted') {

            $pay_load = ['_method'=>'GET'];
            $tETFundServer = new TETFundServer();   /* server class constructor */
            $intervention_types_server_response = $tETFundServer->get_all_data_list_from_server('tetfund-ben-mgt-api/interventions', $pay_load);

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
        
        return redirect(route('tf-bi-submission.submissionRequests.index'));
    }

    /**
     * Update the specified SubmissionRequest in storage.
     *
     * @param  int              $id
     * @param UpdateSubmissionRequestRequest $request
     *
     * @return Response
     */
    public function update(Organization $org, $id, UpdateSubmissionRequestRequest $request)
    {
        /** @var SubmissionRequest $submissionRequest */
        $submissionRequest = SubmissionRequest::find($id);

        if (empty($submissionRequest)) {
            //Flash::error('Submission Request not found');
            return redirect(route('tf-bi-submission.submissionRequests.index'));
        }

        if ($submissionRequest->status == 'not-submitted') {
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
            
            $counter = 1;
            foreach($years as $year) {
                $input['intervention_year'.$counter] = $year;
                $counter += 1;
            }

            $current_user = auth()->user();
            $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();
            
            $input['type'] = 'intervention';
            $input['status'] = 'not-submitted';
            $input['requesting_user_id'] = $current_user->id;
            $input['organization_id'] = $current_user->organization_id;
            $input['beneficiary_id'] = $beneficiary_member->beneficiary_id;

            $submissionRequest->fill($input);
            $submissionRequest->save();
            
            SubmissionRequestUpdated::dispatch($submissionRequest);
            return redirect(route('tf-bi-portal.submissionRequests.show', $submissionRequest->id))->with('success', 'Submission Request updated successfully.')->with('submissionRequest', $submissionRequest);
        }
        return redirect(route('tf-bi-submission.submissionRequests.index'));
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
    public function destroy(Organization $org, $id)
    {
        /** @var SubmissionRequest $submissionRequest */
        $submissionRequest = SubmissionRequest::find($id);

        if (empty($submissionRequest)) {
            //Flash::error('Submission Request not found');

            return redirect(route('tf-bi-submission.submissionRequests.index'));
        }

        $submissionRequest->delete();

        //Flash::success('Submission Request deleted successfully.');
        SubmissionRequestDeleted::dispatch($submissionRequest);
        return redirect(route('tf-bi-submission.submissionRequests.index'));
    }

     
}
