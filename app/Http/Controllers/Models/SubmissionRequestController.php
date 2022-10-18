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


class SubmissionRequestController extends BaseController
{
    /**
     * Display a listing of the SubmissionRequest.
     *
     * @param SubmissionRequestDataTable $submissionRequestDataTable
     * @return Response
     */
    public function index(Organization $org, Request $request) {
        $current_user = Auth()->user();

        $pay_load = array();
        $pay_load['api_detail_page_url'] = url("tf-bi-portal/submissionRequests/");
        $pay_load['_method'] = 'GET';
        if (isset($request->st)) {
            $pay_load['st'] = $request->st;
        }
        if (isset($request->pg)) {
            $pay_load['pg'] = $request->pg;
        }

        /*class constructor*/
        $tETFundServer = new TETFundServer();
        $cdv_submission_requests = $tETFundServer->getAllAndLoadRecordsToDataView('tetfund-bi-submission-api/beneficiary-submission-list', $pay_load);

        if (isset($request->json) && $request->json == true) {
            return $cdv_submission_requests;
        }
        return view('tf-bi-portal::pages.submission_requests.card_view_index')
            ->with('organization', $org)
            ->with('current_user', $current_user)
            ->with('months_list', BaseController::monthsList())
            ->with('states_list', BaseController::statesList())
            ->with('cdv_data_response', $cdv_submission_requests);

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
        //$beneficiary = null;

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

        return view('pages.submission_requests.create')
            ->with("type", 'AIP')
            ->with("intervention_types", array_unique($intervention_types_arr))
            ->with('bi_roles', $bi_roles_arr);
            //->with("beneficiary", $beneficiary->id);
    }

    /**
     * Store a newly created SubmissionRequest in storage.
     *
     * @param CreateSubmissionRequestRequest $request
     *
     * @return Response
     */
    public function store(Organization $org, CreateSubmissionRequestRequest $request)
    {
        $input = $request->all();

        /** @var SubmissionRequest $submissionRequest */
        $submissionRequest = SubmissionRequest::create($input);

        //Flash::success('Submission Request saved successfully.');

        SubmissionRequestCreated::dispatch($submissionRequest);
        return redirect(route('xyz.submissionRequests.index'));
    }

    /**
     * Display the specified SubmissionRequest.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show(Organization $org, $id)
    {
        /** @var SubmissionRequest $submissionRequest */
        $submissionRequest = SubmissionRequest::find($id);

        if (empty($submissionRequest)) {
            //Flash::error('Submission Request not found');

            return redirect(route('tf-bi-submission.submissionRequests.index'));
        }

        return view('pages.submission_requests.show')->with('submissionRequest', $submissionRequest);
    }

    /**
     * Show the form for editing the specified SubmissionRequest.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit(Organization $org, $id)
    {
        /** @var SubmissionRequest $submissionRequest */
        $submissionRequest = SubmissionRequest::find($id);

        if (empty($submissionRequest)) {
            //Flash::error('Submission Request not found');

            return redirect(route('tf-bi-submission.submissionRequests.index'));
        }

        return view('pages.submission_requests.edit')->with('submissionRequest', $submissionRequest);
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

        $submissionRequest->fill($request->all());
        $submissionRequest->save();

        //Flash::success('Submission Request updated successfully.');
        
        SubmissionRequestUpdated::dispatch($submissionRequest);
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
