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

use App\Http\Requests\API\RecallSubmissionRequestAPIRequest;
use App\Http\Requests\API\CreateSubmissionRequestAPIRequest;
use App\Http\Requests\API\UpdateSubmissionRequestAPIRequest;
use App\Http\Requests\API\FollowUpSubmissionRequestAPIRequest;
use App\Http\Requests\API\ReprioritizeSubmissionRequestAPIRequest;
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
                $label = 'Monitoring Request Optional Attachment - '. $request->type; 
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
            $label = 'Monitoring Request Optional Attachment - '. $request->type;
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

        $tetFundServer = new TETFundServer();   /* server class constructor */
        $final_submission_to_tetfund = $tetFundServer->processMRSubmissionRequest($pay_load, $beneficiary->tf_iterum_portal_key_id);

        if (isset($final_submission_to_tetfund->data) && $final_submission_to_tetfund->data != null) {
            $response = $final_submission_to_tetfund->data;

            //update submission request record status
            $submissionRequest->status = 'submitted';
            $submissionRequest->tf_iterum_portal_key_id = $response->id;
            $submissionRequest->tf_iterum_portal_request_status = $response->status;
            $submissionRequest->tf_iterum_portal_response_meta_data = json_encode($response);
            $submissionRequest->tf_iterum_portal_response_at = date('Y-m-d H:i:s');
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

        $tetFundServer = new TETFundServer();   /* server class constructor */
        $clarity_response_to_tetfund = $tetFundServer->processClarificationResponse($pay_load, $request->id);

        if ($clarity_response_to_tetfund == true) {
            return $this->sendSuccess('Submission Request Clarification/Query Response Successfully Sent!');
        }

        return $this->sendError('An unknown error was encountered while processing clarificarion response');
    }

    public function reprioritize(ReprioritizeSubmissionRequestAPIRequest $request, $id) {
        $submissionRequest = SubmissionRequest::find($id);

        if (empty($submissionRequest) || !isset($submissionRequest->tf_iterum_portal_key_id)) {
            return $this->sendError('Submission Request was not found');
        }

        $submissionRequest->amount_requested = $request->reprioritize_amount_requested;
        $submissionRequest->intervention_year1 = $request->reprioritize_intervention_year1 ?? $submissionRequest->intervention_year1;
        $submissionRequest->intervention_year2 = $request->reprioritize_intervention_year2  ?? $submissionRequest->intervention_year2;
        $submissionRequest->intervention_year3 = $request->reprioritize_intervention_year3  ?? $submissionRequest->intervention_year3;
        $submissionRequest->intervention_year4 = $request->reprioritize_intervention_year4  ?? $submissionRequest->intervention_year4;
        $submissionRequest->save();

        // handling reprioritization request optional attachment
        if ($request->hasFile('reprioritize_submission_attachment')) {
            $label = 'Reprioritization Additional Attachment';
            $discription = 'This Document Contains the ' . $label;

            $reprioritize_attachment = $submissionRequest->attach(auth()->user(), $label, $discription, $request->reprioritize_submission_attachment);
        }

        $pay_load = [
            '_method' => 'POST',
            'user_email' => auth()->user()->email,
            'beneficiary_request_id' => $submissionRequest->tf_iterum_portal_key_id,
            'reprioritize_amount_requested' => $submissionRequest->amount_requested,
            'reprioritize_intervention_year1' => $submissionRequest->intervention_year1,
            'reprioritize_intervention_year2' => $submissionRequest->intervention_year2,
            'reprioritize_intervention_year3' => $submissionRequest->intervention_year3,
            'reprioritize_intervention_year4' => $submissionRequest->intervention_year4,
            'reprioritize_comment' => $request->reprioritize_submission_comment ?? null,
            'reprioritize_attachment' => $reprioritize_attachment->attachment ?? null,
        ];

        $tetFundServer = new TETFundServer();   /* server class constructor */
        $tf_reprioritization_response = $tetFundServer->processSubmissionReprioritization($pay_load, $submissionRequest->tf_iterum_portal_key_id);

        if ($tf_reprioritization_response == true) {
            return $this->sendSuccess('Submission Request Reprioritization Processed and Successfully Saved!');
        }

        return $this->sendError('An unknown error was encountered while processing submission reprioritization.');
    }

    public function processFollowUpSubmission(FollowUpSubmissionRequestAPIRequest $request, $id) {
        $submissionRequest = SubmissionRequest::find($id);
        
        if (empty($submissionRequest) && !isset($submissionRequest->tf_iterum_portal_key_id)){
            return $this->sendError('The Submission Request is Invalid.');
        }

        // handling follow-up submission request attachment
        if ($request->hasFile('follow_up_submission_attachment')) {
            $label = 'Submitted Beneficiary Request Follow-up Attachment';
            $discription = 'This Document Contains the ' . $label;

            $follow_up_attachment = $submissionRequest->attach(auth()->user(), $label, $discription, $request->follow_up_submission_attachment);
        }

        $pay_load = [
            '_method' => 'POST',
            'user_email' => auth()->user()->email,
            'beneficiary_request_id' => $submissionRequest->tf_iterum_portal_key_id,
            'follow_up_comment' => $request->follow_up_submission_comment ?? null,
            'follow_up_attachment' => $follow_up_attachment->attachment ?? null,
        ];

        $tetFundServer = new TETFundServer();   /* server class constructor */
        $tf_processed_followUp_response  = $tetFundServer->processFollowUpSubmission($pay_load, $submissionRequest->tf_iterum_portal_key_id);

        if ($tf_processed_followUp_response == true) {
            return $this->sendSuccess('Submission Request Follow-up Processed and Successfully Saved!');
        }

        return $this->sendError('An unknown error was encountered while processing submission follow-up.');
    }

    public function processRecallSubmission(RecallSubmissionRequestAPIRequest $request, $id) {
        $submissionRequest = SubmissionRequest::find($id);
        
        if (empty($submissionRequest) && !isset($submissionRequest->tf_iterum_portal_key_id)){
            return $this->sendError('The Submission Request is Invalid.');
        }

        // handling recall submission request attachment
        if ($request->hasFile('recall_submission_attachment')) {
            $label = 'Letter Recalling Submitted Beneficiary Request';
            $discription = 'This Document Contains the ' . $label;

            $recall_attachment = $submissionRequest->attach(auth()->user(), $label, $discription, $request->recall_submission_attachment);
        }

        $pay_load = [
            '_method' => 'POST',
            'user_email' => auth()->user()->email,
            'beneficiary_request_id' => $submissionRequest->tf_iterum_portal_key_id,
            'recall_comment' => $request->recall_submission_comment ?? null,
            'recall_attachment' => $recall_attachment->attachment ?? null,
        ];

        $tetFundServer = new TETFundServer();   /* server class constructor */
        $tf_processed_followUp_response  = $tetFundServer->processSubmissionRecallRequest($pay_load, $submissionRequest->tf_iterum_portal_key_id);

        if ($tf_processed_followUp_response == true) {
            $submissionRequest->status = 'recalled';
            $submissionRequest->save();

            return $this->sendSuccess('Recalling Submission Request Processed and Successfully Saved!');
        }

        return $this->sendError('An unknown error was encountered while recalling this submission.');
    }

    public function ongoingSubmission(Organization $org, Request $request, $ongoing_label) {
        if (isset($ongoing_label) && ($ongoing_label=='1st_Tranche_Payment' || $ongoing_label=='2nd_Tranche_Payment' || $ongoing_label=='Final_Tranche_Payment' || $ongoing_label=='Monitoring_Request')) {

            $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', auth()->user()->id)->first();
            
            $pay_load = ['_method'=>'GET', 'beneficiary_type'=>$beneficiary_member->beneficiary->type ?? null];
            
            $tetFundServer = new TETFundServer();   /* server class constructor */
            $intervention_types = $tetFundServer->get_all_data_list_from_server('tetfund-ben-mgt-api/interventions', $pay_load);

            $view_html_response = view("pages.submission_requests.partials.ongoing_submission_input_fields")
                ->with("ongoing_label", $ongoing_label)
                ->with("intervention_types", $intervention_types)
                ->render();

            return $this->sendResponse("<hr> $view_html_response", 'Ongoing submission form data successfully retrieved');
        } else {
            $html_error_response = "
                <div class='col-sm-12 text-center text-danger'>
                    <b><i>
                        Ongoing Submission Request not processable for ". str_replace('_', ' ', $ongoing_label) .".
                    </i></b>
                </div>";
            return $this->sendResponse("<hr> $html_error_response", 'Error: Invalid Ongoing Submission Request Stage Selected');
        }
    }

    public function processOngoingSubmission(Organization $org, CreateSubmissionRequestAPIRequest $request) {
        // array of intervention years provided with request
        $intevention_years = ['0'];
        for ($i=1; $i<=4; ++$i) {
            if(isset($request->{'intervention_year'.$i})) {
                array_push($intevention_years, $request->{'intervention_year'.$i});
            }
        }

        $intevention_years_unique = array_unique($intevention_years);
        sort($intevention_years_unique);

        $well_formatted_type = str_replace('_', ' ', $request->ongoing_submission_stage);
        $ongoingSubmission = SubmissionRequest::where('type', $well_formatted_type)
                                ->where('tf_iterum_intervention_line_key_id', $request->tf_iterum_intervention_line_key_id)
                                ->whereIn('intervention_year1', $intevention_years_unique)
                                ->whereIn('intervention_year1', $intevention_years_unique)
                                ->whereIn('intervention_year2', $intevention_years_unique)
                                ->whereIn('intervention_year3', $intevention_years_unique)
                                ->whereIn('intervention_year4', $intevention_years_unique)
                                ->first();

        if (!empty($ongoingSubmission)) {
            return response()->JSON([
                'errors'=>["A Submission Request for the selected intervention line, intervention years and ongoing submission stage already exist."]
            ]);
        }

        $y_counter = 1;
        $current_user = auth()->user();
        $disbursement_percentage = null;
        array_shift($intevention_years_unique);
        $ongoingSubmission = new SubmissionRequest();
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();
         
        $ongoingSubmission->organization_id = $org->id;
        $ongoingSubmission->title = $request->title .' - '. $well_formatted_type .' - ('. implode(', ', $intevention_years_unique) . ')';
        $ongoingSubmission->status = 'not-submitted';
        $ongoingSubmission->type = $well_formatted_type;
        $ongoingSubmission->requesting_user_id = $current_user->id;
        $ongoingSubmission->beneficiary_id = $beneficiary_member->beneficiary_id??null;
        
        foreach($intevention_years_unique as $year) {
            $ongoingSubmission->{'intervention_year'.$y_counter} = $year;
            $y_counter += 1;
        }

        $ongoingSubmission->proposed_request_date = date('Y-m-d H:i:s');
        $ongoingSubmission->tf_iterum_intervention_line_key_id = $request->tf_iterum_intervention_line_key_id;

        if ($request->ongoing_submission_stage=='1st_Tranche_Payment') {
            $ongoingSubmission->is_first_tranche_request = true;
            $disbursement_percentage = $ongoingSubmission->first_tranche_intervention_percentage($request->title);
        } elseif ($request->ongoing_submission_stage=='2nd_Tranche_Payment') {
            $ongoingSubmission->is_second_tranche_request = true;
            $disbursement_percentage = $ongoingSubmission->second_tranche_intervention_percentage($request->title);
        } elseif ($request->ongoing_submission_stage=='Final_Tranche_Payment') {
            $ongoingSubmission->is_final_tranche_request = true;
            $disbursement_percentage = $ongoingSubmission->final_tranche_intervention_percentage($request->title);
        } elseif ($request->ongoing_submission_stage=='Monitoring_Request') {
            $ongoingSubmission->is_monitoring_request = true;
        }

        if ($disbursement_percentage == null) {
            return response()->JSON([
                'errors'=>["The ongoing submission request stage does not applies to the intervention line selected."]
            ]);
        }

        $disbursement_percentage = str_replace('%', '', $disbursement_percentage);
        $ongoingSubmission->amount_requested = ($request->amount_requested * floatval($disbursement_percentage)) / 100;
        $ongoingSubmission->save();

        // handling array of file attachments
        if($request->hasfile('file_attachments')){
            $attach_ct = 0;
            foreach($request->file('file_attachments') as $file){
                $label = 'Ongoing Submission Request Attachment No. '. ++$attach_ct;
                $discription = 'This Document Contains the ' . $label;

                $ongoing_attachable = $ongoingSubmission->attach($current_user, $label, $discription, $file);
            }
        }

        return $this->sendResponse($ongoingSubmission, 'Ongoing submission request saved successfully.');
    }
}
