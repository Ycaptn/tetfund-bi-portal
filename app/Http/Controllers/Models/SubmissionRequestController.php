<?php

namespace App\Http\Controllers\Models;

use App\Models\SubmissionRequest;

use App\Events\SubmissionRequestCreated;
use App\Events\SubmissionRequestUpdated;
use App\Events\SubmissionRequestDeleted;

use App\Http\Requests\CreateSubmissionRequestRequest;
use App\Http\Requests\UpdateSubmissionRequestRequest;

use App\DataTables\SubmissionRequestDataTable;

use Hasob\FoundationCore\Controllers\BaseController;
use Hasob\FoundationCore\Models\Organization;

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
    public function index(Organization $org, SubmissionRequestDataTable $submissionRequestDataTable) {
        $current_user = Auth()->user();
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();

        $cdv_submission_requests = new \Hasob\FoundationCore\View\Components\CardDataView(SubmissionRequest::class, "pages.submission_requests.card_view_item");
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
        $bi_roles = auth()->user()->roles;
        $bi_roles_arr = array();
        $intervention_types_arr = [];

        if (count($bi_roles) > 0) {
            foreach ($bi_roles as $role) {
                array_push($bi_roles_arr, $role->name);
            }
        }

        $pay_load = ['_method'=>'GET'];
        $tETFundServer = new TETFundServer();   /* server class constructor */
        $intervention_types_server_response = $tETFundServer->get_all_data_list_from_server('tetfund-ben-mgt-api/interventions', $pay_load);

        if (count($intervention_types_server_response) > 0) {
            foreach ($intervention_types_server_response as $intervention_type) {
                array_push($intervention_types_arr, $intervention_type->type);        
            }
        }

          $years = [];
          for ($i=0; $i < 6; $i++) { 
              array_push($years, date("Y")-$i);
          }

        return view('pages.submission_requests.create')
            ->with("type", 'AIP')
            ->with("years", $years)
            ->with("intervention_types", array_unique($intervention_types_arr))
            ->with('bi_roles', $bi_roles_arr);
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

    public function processSubmissionRequestAttachement(Request $request, $id){
        /*implement processing success*/
        return redirect()->back()->withErrors(["Oops!... OCCURING ERROR(s): The request submission must contain all required Attachment(s).", "Oops!... OCCURING ERROR(s): The request submission must contain all required Attachment(s)."]);
       

    }

    public function processSubmissionRequestToTFPortal(Request $request){
        /*implement processing success*/
        return redirect()->back()->withErrors(["The request submission must contain all required Attachment(s).", "Fund requested must be equal to the Allocated amount"]);
    }

    /**
     * Display the specified SubmissionRequest.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show(Organization $org, $id, Request $request) {
        /** @var SubmissionRequest $submissionRequest */
        $submissionRequest = SubmissionRequest::find($id);

        if (empty($submissionRequest)) {
            //Flash::error('Submission Request not found');
            return redirect(route('tf-bi-portal.submissionRequests.index'));
        }
        
        /* get intervention model artifacts */
        $pay_load = [
            '_method' => 'POST',
            'model_name' => strval("TETFund\BeneficiaryMgt\Models\Intervention"),
            'model_primary_id' => $submissionRequest->tf_iterum_intervention_line_key_id
        ];
        $tETFundServer = new TETFundServer();   /* server class constructor */
        $existing_model_artifact = $tETFundServer->get_all_data_list_from_server('tetfund-ben-mgt-api/interventions/get_managing_checklist', $pay_load);

        /* get checklist binded to specified intervention model artifact */
        $checklist_items = null;
        $existing_model_artifact_arr = [];
        foreach($existing_model_artifact as $model_artifact){
            array_push($existing_model_artifact_arr, $model_artifact->value);
        }

        if (count($existing_model_artifact) > 0 && count($existing_model_artifact_arr) > 0) {
            $pay_load = [ '_method' => 'POST', 'list_name' => $existing_model_artifact_arr ];
            $tETFundServer = new TETFundServer();   /* server class constructor */
            $checklist_items = $tETFundServer->get_all_data_list_from_server('fc-api/get_filter_checklist', $pay_load);
        }

        /* get all interventions from server */
        $pay_load = ['_method'=>'GET', 'id'=>$submissionRequest->tf_iterum_intervention_line_key_id];
        $tETFundServer = new TETFundServer();   /* server class constructor */
        $intervention_types_server_response = $tETFundServer->get_row_records_from_server("tetfund-ben-mgt-api/interventions/".$submissionRequest->tf_iterum_intervention_line_key_id, $pay_load);

        return view('pages.submission_requests.show')
            ->with('intervention', $intervention_types_server_response)
            ->with('submissionRequest', $submissionRequest)
            ->with('checklist_items', $checklist_items);
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

        $bi_roles = auth()->user()->roles;
        $bi_roles_arr = array();
        $intervention_types_arr = [];

        if (count($bi_roles) > 0) {
            foreach ($bi_roles as $role) {
                array_push($bi_roles_arr, $role->name);
            }
        }

        $pay_load = ['_method'=>'GET'];
        $tETFundServer = new TETFundServer();   /* server class constructor */
        $intervention_types_server_response = $tETFundServer->get_all_data_list_from_server('tetfund-ben-mgt-api/interventions', $pay_load);

        if (count($intervention_types_server_response) > 0) {
            foreach ($intervention_types_server_response as $intervention_type) {
                $intervention_types_arr[$intervention_type->id] = $intervention_type->type;
            }
        }

        $years = [];
        for ($i=0; $i < 6; $i++) { 
          array_push($years, date("Y")-$i);
        }

        return view('pages.submission_requests.edit')
            ->with('submissionRequest', $submissionRequest)
            ->with("years", $years)
            ->with("intervention_types", array_unique($intervention_types_arr))
            ->with('bi_roles', $bi_roles_arr);
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

        $input = $request->all();
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
