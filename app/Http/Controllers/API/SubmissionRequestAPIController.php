<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\SubmissionRequest;
use App\Models\BeneficiaryMember;
use App\Models\NominationRequest;

use App\Events\SubmissionRequestCreated;
use App\Events\SubmissionRequestUpdated;
use App\Events\SubmissionRequestDeleted;

use App\Http\Requests\API\CreateSubmissionRequestAPIRequest;
use App\Http\Requests\API\UpdateSubmissionRequestAPIRequest;

use Hasob\FoundationCore\Traits\ApiResponder;
use Hasob\FoundationCore\Models\Organization;

use Hasob\FoundationCore\Controllers\BaseController as AppBaseController;
use App\Managers\TETFundServer;

/**
 * Class SubmissionRequestController
 * @package App\Http\Controllers\API
 */

class SubmissionRequestAPIController extends AppBaseController
{

    use ApiResponder;

    /**
     * Display a listing of the SubmissionRequest.
     * GET|HEAD /submissionRequests
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, Organization $organization) {
        $query = SubmissionRequest::query();

        if ($request->get('skip')) {
            $query->skip($request->get('skip'));
        }
        if ($request->get('limit')) {
            $query->limit($request->get('limit'));
        }
        
        if ($organization != null){
            $query->where('organization_id', $organization->id);
        }

        $submissionRequests = $this->showAll($query->get());

        return $this->sendResponse($submissionRequests->toArray(), 'Submission Requests retrieved successfully');
    }

    /**
     * Store a newly created SubmissionRequest in storage.
     * POST /submissionRequests
     *
     * @param CreateSubmissionRequestAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateSubmissionRequestAPIRequest $request, Organization $organization) {

        $input = $request->all();

        $current_user = auth()->user();
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();
        
        if (!$request->has('is_monitoring_request') || ($request->has('is_monitoring_request') && $request->is_monitoring_request == false)) {
            
            $input['type'] = $request->request_tranche ?? 'Request for AIP';
        }

        $input['status'] = 'not-submitted';
        $input['requesting_user_id'] = $current_user->id;
        $input['organization_id'] = $current_user->organization_id;
        $input['beneficiary_id'] = $beneficiary_member->beneficiary_id;

        /** @var SubmissionRequest $submissionRequest */
        $submissionRequest = SubmissionRequest::create($input);

        // handling monitoring request optional attachment
        if ($request->has('is_monitoring_request') && $request->is_monitoring_request == true && $request->hasFile('optional_attachment')) {                
                $label = 'Monitoring Request Optional Attachment - '. $request->title; 
                $discription = 'This Document Contains the ' . $label ;
                $submissionRequest->attach(auth()->user(), $label, $discription, $request->optional_attachment);
        }

        /*Dispatch event*/
        SubmissionRequestCreated::dispatch($submissionRequest);
        return $this->sendResponse($submissionRequest->toArray(), 'Submission Request saved successfully');
    }

    /**
     * Display the specified SubmissionRequest.
     * GET|HEAD /submissionRequests/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id, Organization $organization)
    {
        /** @var SubmissionRequest $submissionRequest */
        $submissionRequest = SubmissionRequest::find($id);

        if (empty($submissionRequest)) {
            return $this->sendError('Submission Request not found');
        }

        return $this->sendResponse($submissionRequest->toArray(), 'Submission Request retrieved successfully');
    }

    /*
        return related nominations type
    */
    public function get_all_related_nomination_request(Request $request, $type) {
        $relationship = 'user';
        if ($type == 'tp') {
            $relationship = 'tp_submission';
        } elseif ($type == 'ca') {
            $relationship = 'ca_submission';
        } elseif ($type == 'tsas') {
            $relationship = 'tsas_submission';
        }

        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', auth()->user()->id)->first();
        $related_nomination_request = NominationRequest::with($relationship)
                            ->where('type', $type)
                            ->where('beneficiary_id', optional($beneficiary_member)->beneficiary_id)
                            ->where('status', 'approved')
                            ->where('details_submitted', true)
                            ->where('is_desk_officer_check', true)
                            ->where('is_average_committee_members_check', true)
                            ->orderBy('updated_at', 'DESC')
                            ->whereNull('bi_submission_request_id')
                            ->get();

        return $this->sendResponse($related_nomination_request->toArray(), 'All Nominmation requests retrieved successfully!');
    }


    /**
     * Update the specified SubmissionRequest in storage.
     * PUT/PATCH /submissionRequests/{id}
     *
     * @param int $id
     * @param UpdateSubmissionRequestAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSubmissionRequestAPIRequest $request, Organization $organization)
    {
        /** @var SubmissionRequest $submissionRequest */
        $submissionRequest = SubmissionRequest::find($id);

        if (empty($submissionRequest)) {
            return $this->sendError('Submission Request not found');
        }

        $submissionRequest->fill($request->all());
        $submissionRequest->save();
        
        SubmissionRequestUpdated::dispatch($submissionRequest);
        return $this->sendResponse($submissionRequest->toArray(), 'SubmissionRequest updated successfully');
    }

    /**
     * Remove the specified SubmissionRequest from storage.
     * DELETE /submissionRequests/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id, Organization $organization, Request $request) {
        /** @var SubmissionRequest $submissionRequest */
        $submissionRequest = SubmissionRequest::find($id);

        if (empty($submissionRequest)) {
            return $this->sendError('Submission Request not found');
        }

        //handles submissions for attachments
        if (isset($request->submissionRequestId) && isset($request->attachment_label) && $submissionRequest != null) {
            $submissionRequest->delete_attachment($request->attachment_label);
            return $this->sendSuccess('Submission Request Attachment deleted successfully');
        }

        $submissionRequest->delete();
        SubmissionRequestDeleted::dispatch($submissionRequest);
        return $this->sendSuccess('Submission Request deleted successfully');
    }
}
