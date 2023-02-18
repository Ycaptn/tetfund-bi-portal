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
use App\Http\Requests\API\SubmissionClarificationResponseAPIRequest;

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
    public function update($id, UpdateSubmissionRequestAPIRequest $request, Organization $organization) {

        /** @var SubmissionRequest $submissionRequest */
        $submissionRequest = SubmissionRequest::find($id);

        if (empty($submissionRequest)) {
            return $this->sendError('Submission Request not found');
        }

        $submissionRequest->fill($request->all());
        $submissionRequest->save();
        
        // handling monitoring request optional attachment
        if ($request->has('is_monitoring_request') && $request->is_monitoring_request == true && $request->hasFile('optional_attachment')) {
            $label = 'Monitoring Request Optional Attachment - '. $request->title;
            $discription = 'This Document Contains the ' . $label;

            // deleting old attachments if any
            $attachments = $submissionRequest->get_all_attachments($submissionRequest->id);
            if ($attachments != null) {
                foreach ($attachments as $attached) {
                    $submissionRequest->delete_attachment($attached->label);
                }
            }

            $submissionRequest->attach(auth()->user(), $label, $discription, $request->optional_attachment);
        }

        /*Dispatch event*/
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

    public function process_m_r_to_tetfund($id, Organization $organization, Request $request) {
        $current_user = auth()->user();
        $submissionRequest = SubmissionRequest::find($id);

        if (empty($submissionRequest)) {
            return $this->sendError('Submission Request not found');
        }

        if($current_user->hasRole('BI-desk-officer') == false) {
            return $this->sendError('Please, Kindly Contact the Institution TETFund Desk Officer, as only them has the Privilege to Submit This Request');
        }

        $beneficiary = $submissionRequest->beneficiary;
        $submission_attachment_array = $submissionRequest->get_all_attachments($submissionRequest->id);

        $pay_load = $submissionRequest->toArray();
        $pay_load['_method'] = 'POST';
        $pay_load['submission_attachment_array'] = $submission_attachment_array ?? [];
        $pay_load['tf_iterum_aip_request_id'] = $submissionRequest->getParentAIPSubmissionRequest()->tf_iterum_portal_key_id ?? null;
        $pay_load['tf_beneficiary_id'] = $beneficiary->tf_iterum_portal_key_id;
        $pay_load['submission_user'] = $current_user;

        $tETFundServer = new TETFundServer();   /* server class constructor */
        $final_submission_to_tetfund = $tETFundServer->processMRSubmissionRequest($pay_load, $beneficiary->tf_iterum_portal_key_id);

        if (isset($final_submission_to_tetfund->data) && $final_submission_to_tetfund->data != null) {
            $response = $final_submission_to_tetfund->data;

            //update submission request record status
            $submissionRequest->status = 'submitted';
            $submissionRequest->tf_iterum_portal_key_id = $response->id;
            $submissionRequest->tf_iterum_portal_request_status = $response->status;
            $submissionRequest->tf_iterum_portal_response_meta_data = json_encode($response);
            $submissionRequest->tf_iterum_portal_response_at = date('Y-m-d');
            $submissionRequest->save();

            $success_message = "This Request Has Now Been Successfully Submitted To TETFund!!";
            return $this->sendResponse($submissionRequest->toArray(), 'Submission of Monitoring Request successfully completed');
        }

        return $this->sendError('Oops!!!, An unknown error was encountered while processing final submission.');
    }

    public function clarification_response(SubmissionClarificationResponseAPIRequest $request) {
        
        $submissionRequest = SubmissionRequest::find($request->submission_request_id);
        $pay_load = [
            '_method' => 'POST',
            'id' => $request->id,
            'user_email' => auth()->user()->email,
            'beneficiary_request_id' => $submissionRequest->tf_iterum_portal_key_id,
            'text_clarificarion_response' => $request->text_clarificarion_response
        ];

        // handling monitoring request optional attachment
        if ($request->hasFile('attachment_clarificarion_response')) {
            $label = 'Clarification Response Optional Attachment';
            $discription = 'This Document Contains the ' . $label;

            // deleting old attachments if any
            $attachments = $submissionRequest->get_specific_attachment($submissionRequest->id, $label);
            if ($attachments != null) {
                $submissionRequest->delete_attachment($attachments->label);
            }

            $clarification_attachable = $submissionRequest->attach(auth()->user(), $label, $discription, $request->attachment_clarificarion_response);
        }

        $pay_load['attachment_clarificarion_response'] = $clarification_attachable->attachment ?? null;

        $tETFundServer = new TETFundServer();   /* server class constructor */
        $clarity_response_to_tetfund = $tETFundServer->processClarificationResponse($pay_load, $request->id);

        if ($clarity_response_to_tetfund == true) {
            return $this->sendSuccess('Submission Request Clarification/Query Response Successfully Sent!');
        }

        return $this->sendError('An unknown error was encountered while processing clarificarion response');
    }
}
