<?php

namespace App\Http\Controllers\API;

use App\Models\NominationRequest;
use Hasob\FoundationCore\Controllers\BaseController;

use Flash;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Hasob\FoundationCore\Traits\ApiResponder;
use Hasob\FoundationCore\Models\Organization;
use Hasob\FoundationCore\Models\User;
use App\Http\Requests\API\CreateNominationRequestAPIRequest;
use App\Http\Requests\API\CreateTPNominationAPIRequest;
use App\Http\Requests\API\CreateCANominationAPIRequest;
use App\Http\Requests\API\CreateTSASNominationAPIRequest;
use App\Http\Controllers\API\TPNominationAPIController;
use App\Http\Controllers\API\CANominationAPIController;
use App\Http\Controllers\API\TSASNominationAPIController;
use App\Http\Traits\BeneficiaryUserTrait;
use App\Models\BeneficiaryMember;
use App\Models\Beneficiary;
use App\Models\SubmissionRequest;
use App\Models\NominationCommitteeVotes;
use App\Models\TPNomination;
use App\Models\CANomination;
use App\Models\TSASNomination;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NominationRequestInviteNotification;
use App\Managers\TETFundServer;

class NominationRequestAPIController extends BaseController
{
    use ApiResponder;
    use BeneficiaryUserTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Organization $org)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateNominationRequestAPIRequest $request) {
        
        $current_user = auth()->user();     //current user
                
        $bi_beneficiary_id = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first()->beneficiary_id;   //BI beneficiary_id

        $bi_beneficiary = Beneficiary::find($bi_beneficiary_id);    //BI beneficiary

        //checking if staff is making request themselves
        if ($current_user->hasAnyRole(['BI-staff'])) {

            $process_response = self::staffMakesNominationRequest($request->all(), $bi_beneficiary_id);

            if ($process_response == false) {
                return $this->sendError("Oops... An unknown error when processing Nomination Request");
            }

            return $this->sendResponse($process_response, "Nomination Request has been submitted successfully");
        }

        //check if invited user's email exist
        $invited_user = User::where('email', $request->bi_staff_email)->first();

        if (empty($invited_user) || $invited_user == null) {
            //new beneficiary staff payload
            $pay_load = [
                "email" => $request->bi_staff_email,
                "first_name" => ucwords($request->bi_staff_fname),
                "last_name" => ucwords($request->bi_staff_lname),
                "telephone" => $request->bi_telephone,
                'password' => 'password',
                "gender" => ucwords($request->bi_staff_gender),
                'organization_id' => $current_user->organization_id,
                'beneficiary_bi_id' => $bi_beneficiary_id,
                'beneficiary_tetfund_iterum_id' => $bi_beneficiary->tf_iterum_portal_key_id,
                'user_roles_arr' => ['BI-staff']
            ];
            
            // creating beneficiary staff user
            $invited_user_response = $this->create_new_bims_and_local_user($pay_load);
            $invited_user = User::find($invited_user_response['beneficiary_user_id']);
        }
        
        /*Create Nomination Request and send invitation email*/    
        $nominationRequest = new NominationRequest();
        $nominationRequest->organization_id = $current_user->organization_id;
        $nominationRequest->user_id = $invited_user->id;
        $nominationRequest->beneficiary_id =$bi_beneficiary_id;
        $nominationRequest->type = $request->nomination_type;
        $nominationRequest->request_date = date('Y-m-d');
        $nominationRequest->status = 'approved';
        $nominationRequest->save();

        //send nomination request invitation mail notices to invited_user 
        Notification::send($invited_user, new NominationRequestInviteNotification($request->all()));

        return $this->sendResponse($invited_user->toArray(), "Nomination Invitation has been sent to " . $request->bi_staff_email . " successfully");
    }


    //store staff nomination request and nomination request data
    public function store_nomination_request_and_details(Request $request) {
        if (!$request->has('nomination_type') || $request->nomination_type != 'tp' || $request->nomination_type != 'ca' || $request->nomination_type != 'tsas') {
            $this->sendError('Invalid Nomination Type Selected');
        }
        
        $input = $request->all();

        if ($request->nomination_type == 'tp') {
            $request = app('App\Http\Requests\API\CreateTPNominationAPIRequest');
          
            $this->validate($request, $request->rules());   // validate for TP  
            $nominationRequestOBJ = new TPNomination();

            $nominationRequestAPIControllerOBJ = new TPNominationAPIController();   // hitting TP API Controller

            /* server class constructor to retrieve amout settings */
            $pay_load = [ '_method' => 'GET', 'query_like_parameters' => 'tp_', ];
            $tETFundServer = new TETFundServer(); 
            $tp_amount_settings = $tETFundServer->get_all_data_list_from_server('tetfund-astd-api/dashboard/get_configured_amounts', $pay_load);

            $dta_amount = floatval($tp_amount_settings->{'tp_'.strtolower($request->rank_gl_equivalent).'_dta_amount'}) ?? 0;
            $dta_no_days = floatval($tp_amount_settings->{'tp_'.strtolower($request->rank_gl_equivalent).'_dta_nights_amount'}) ?? 0;
            $taxi_fare_amount = floatval($tp_amount_settings->{'tp_'.strtolower($request->rank_gl_equivalent).'_taxi_fare_amount'}) ?? 0;

            // setting amount colums
            $input['dta_amount_requested'] = $dta_amount;
            $input['dta_nights_amount_requested'] = $dta_amount * $dta_no_days;
            $input['local_runs_amount_requested'] = (30 * ($dta_amount * $dta_no_days)) / 100;
            $input['taxi_fare_amount_requested'] = $taxi_fare_amount;
            $input['total_requested_amount'] = $input['dta_nights_amount_requested'] + $input['local_runs_amount_requested'] + $taxi_fare_amount;

        } else if ($request->nomination_type == 'ca') {    
            $request = app('App\Http\Requests\API\CreateCANominationAPIRequest');
        
            $this->validate($request, $request->rules());   // validate for CA
        
            $nominationRequestOBJ = new CANomination();
        
            //hitting TSAS API Controller
            $nominationRequestAPIControllerOBJ = new CANominationAPIController();
        } else if ($request->nomination_type == 'tsas') {
            $request = app('App\Http\Requests\API\CreateTSASNominationAPIRequest');
        
            $this->validate($request, $request->rules());   // validate for TSAS
        
            $nominationRequestAPIControllerOBJ = new TSASNominationAPIController();  

            //hitting TSAS API Controller
            $nominationRequestOBJ = new TSASNomination();
        }
        
        // nomination request creation
        $current_user = auth()->user();
        $bi_beneficiary_id = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first()->beneficiary_id;

        $nominationRequest = new NominationRequest();   
        $nominationRequest->organization_id = $current_user->organization_id;
        $nominationRequest->user_id = $current_user->id;
        $nominationRequest->beneficiary_id =$bi_beneficiary_id;
        $nominationRequest->type = $request->nomination_type;
        $nominationRequest->details_submitted = 1;
        $nominationRequest->request_date = date('Y-m-d');
        $nominationRequest->status = 'approved';
        $nominationRequest->save();

        // handle nomination request details submission
        $input['user_id'] = $current_user->id;
        $input['nomination_request_id'] = $nominationRequest->id;
        $input['beneficiary_institution_id'] = $bi_beneficiary_id;

        $nomination_details = $nominationRequestOBJ->create($input);
        
        /*handling attachemnt upload process*/
        $attachement_and_final_response = $nominationRequestAPIControllerOBJ->handle_attachments($request, $nomination_details, $nominationRequest);
        if ($attachement_and_final_response) {
            return $attachement_and_final_response;
        }

        return $this->sendError('Error encountered while processing attachements');        
    }

    /* staff Makes Nomination Request */
    private function staffMakesNominationRequest($request_arr, $bi_beneficiary_id) {
        
        $current_user = auth()->user();     //current user

        /*Create Nomination Request and send invitation email*/    
        $nominationRequest = new NominationRequest();
        $nominationRequest->organization_id = $current_user->organization_id;
        $nominationRequest->user_id = $current_user->id;
        $nominationRequest->beneficiary_id =$bi_beneficiary_id;
        $nominationRequest->type = $request_arr['nomination_type'];
        $nominationRequest->request_date = date('Y-m-d');
        $nominationRequest->status = 'pending';
        $nominationRequest->save();

        if (isset($nominationRequest->id) && $nominationRequest->id != null) {
            return true;
        }
        return false;

    }


    /**
     * Display the specified resource.
     * @return \Illuminate\Http\Response
     */
    public function show($id)  {  
        $nominationRequest = NominationRequest::find($id);
        if (empty($nominationRequest)) {
            return $this->sendError('Nomination Request not found');
        }

        $current_user = auth()->user();
        $beneficiary_members = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();

        $nominationRequestDetails = $nominationRequest->toArray();
        $nominationRequestDetails['nomination_request_type'] = $nominationRequest->type;
        if (strtolower($nominationRequest->type) == 'tp') {
            $nominationRequestDetails['nominee'] = $nominationRequest->tp_submission;
        } elseif (strtolower($nominationRequest->type) == 'ca') {
            $nominationRequestDetails['nominee'] = $nominationRequest->ca_submission;
        } elseif (strtolower($nominationRequest->type) == 'tsas') {
            $nominationRequestDetails['nominee'] = $nominationRequest->tsas_submission;
        }
        $nominationRequestDetails['attachments'] = $nominationRequest->get_all_attachments($nominationRequest->id);
        $nominationRequestDetails['nominee_beneficiary'] = $nominationRequest->beneficiary;
        $nominationRequestDetails['submission_request'] = $nominationRequest->submission_request;

        if ($current_user->hasRole('BI-'.strtoupper($nominationRequest->type).'-committee-head')) {
            //voters for nominee
            $nomination_committee_voters = [];  
            if (count($nominationRequest->nomination_committee_votes) > 0) {
                foreach ($nominationRequest->nomination_committee_votes as $key => $value) {
                    array_push($nomination_committee_voters, array_merge($value->user->toArray(), ['approval_comment'=>$value->approval_comment, 'approval_status'=>$value->approval_status,]));
                }
            }
            $nominationRequestDetails['nomination_committee_voters'] = $nomination_committee_voters;
            $nominationRequestDetails['count_committee_votes'] = count($nomination_committee_voters);

            //all commitee members for specific type of nomination
            $beneficiary_committee_members = User::role(['BI-'.strtoupper($nominationRequest->type).'-committee-member', 'BI-'.strtoupper($nominationRequest->type).'-committee-head'])
                    ->join('tf_bi_beneficiary_members', 'tf_bi_beneficiary_members.beneficiary_user_id', 'fc_users.id', )
                    ->where('tf_bi_beneficiary_members.beneficiary_id', $beneficiary_members->beneficiary_id)
                    ->select('fc_users.*', 'tf_bi_beneficiary_members.beneficiary_id')
                    ->get();
            $nominationRequestDetails['beneficiary_committee_members'] = $beneficiary_committee_members;
            $nominationRequestDetails['count_committee_members'] = count($beneficiary_committee_members);

        }

        return $this->sendResponse($nominationRequestDetails, 'Nomination Request details retrieved successfully');
    }

    //process committee members approval
    public function process_committee_member_vote(Request $request, $id) {
        $nominationRequest = NominationRequest::find($id);
        if (empty($nominationRequest)) {
            return self::createJSONResponse("fail","error",["Nomination Request is not found"],200);
        }

        $current_user = auth()->user();
        $beneficiary_members = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();

        // checking if any decision was made or selected
        $fields_err = [];
        if (!($request->has('decision')) || $request->decision == 'undefined') {
            array_push($fields_err, "The Decision option must be selected.");
        }
        if (!($request->has('comment')) || $request->comment == null) {
            array_push($fields_err, "The Decision Comment input is required.");
        }
        
        /*if (($request->has('comment')) && $request->comment != null && strlen($request->comment) < 10) {
            array_push($fields_err, "The Decision Comment input must contain more than 10 characters.");
        }*/

        if (count($fields_err) > 0) {
            return self::createJSONResponse("fail","error",$fields_err,200);
        }

        //checking if user has role to vote
        $role_allowed = ['BI-'.strtoupper($nominationRequest->type).'-committee-member', 'BI-'.strtoupper($nominationRequest->type).'-committee-head'];
        if (!($current_user->hasAnyRole($role_allowed))) {
            return self::createJSONResponse("fail","error",["Current user doesn't has the role to make consideration"],200);
        }
        
        //checking committee member is from same institution
        $nomination_request_name = strtoupper($nominationRequest->type).'Nomination';
        if ($nominationRequest->beneficiary_id != optional($beneficiary_members)->beneficiary_id) {
            $err_msg = "This " . $nomination_request_name . " Request doesn't belong to your Institution";
            return self::createJSONResponse("fail","error",[$err_msg],200);
        }

        //checking if this user has voted before
        $committee_member_vote = NominationCommitteeVotes::where([
                                    'user_id' => $current_user->id,
                                    'beneficiary_id' => $beneficiary_members->beneficiary_id,
                                    'nomination_request_id' => $nominationRequest->id
                                ])->first();
        if (!empty($committee_member_vote)) {
            $err_msg = "DECISION REJECTED: You cannot make any consideration for this " . $nomination_request_name . " Request more than Once!";
            return self::createJSONResponse("fail","error",[$err_msg],200);
        }

        //finally save vote for nomination request
        $committee_member_vote = new NominationCommitteeVotes();
        $committee_member_vote->organization_id = $current_user->organization_id;
        $committee_member_vote->user_id = $current_user->id;
        $committee_member_vote->beneficiary_id = $beneficiary_members->beneficiary_id;
        $committee_member_vote->nomination_request_id = $nominationRequest->id;
        $committee_member_vote->approval_status = (strtolower($request->decision) == 'approved' ? true : false);
        $committee_member_vote->approval_comment = trim($request->comment);
        $committee_member_vote->additional_param = $nomination_request_name;
        $committee_member_vote->save(); 

        return $this->sendSuccess('Your Approval vote has been counted for the selected ' . $nomination_request_name . ' Request');
    }

    public function process_committee_head_consideration(Request $request, $id) {
        $nominationRequest = NominationRequest::find($id);
        if (empty($nominationRequest)) {
            return self::createJSONResponse("fail","error",["Nomination Request is not found"],200);
        }

        $current_user = auth()->user();

        //checking if user has role to vote
        $role_allowed = ['BI-'.strtoupper($nominationRequest->type).'-committee-head'];
        if (!($current_user->hasAnyRole($role_allowed))) {
            return self::createJSONResponse("fail","error",["Current user doesn't has the role to make consideration"],200);
        }

        // checking if any decision was made or selected
        $fields_err = [];
        if (!($request->has('decision')) || $request->decision == 'undefined') {
            array_push($fields_err, "The Decision option must be selected.");
        }
        if (!($request->has('comment')) || $request->comment == null) {
            array_push($fields_err, "The Comment input is required.");
        }
        
        // return input fields errorrs
        if (count($fields_err) > 0) {
            return self::createJSONResponse("fail","error",$fields_err,200);
        }

        /*$beneficiary_members = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();*/

        //voters for nominee with approve status
        /*$nomination_committee_voters_for = NominationCommitteeVotes::where([
                    'beneficiary_id' => $beneficiary_members->beneficiary_id,
                    'nomination_request_id' => $nominationRequest->id,
                    'approval_status' => true,
                ])->get();*/

        //voters for nominee with declined status
        /*$nomination_committee_voters_against = NominationCommitteeVotes::where([
                    'beneficiary_id' => $beneficiary_members->beneficiary_id,
                    'nomination_request_id' => $nominationRequest->id,
                    'approval_status' => false,
                ])->get();*/

        // possible roles allowed based on nomination
        /*$committee_member_role = ['BI-'.strtoupper($nominationRequest->type).'-committee-member'];*/

        //all committee members for specific type of nomination
        /*$beneficiary_committee_members = User::role($committee_member_role)
            ->join('tf_bi_beneficiary_members', 'tf_bi_beneficiary_members.beneficiary_user_id', 'fc_users.id')
            ->where('tf_bi_beneficiary_members.beneficiary_id', $beneficiary_members->beneficiary_id)
            ->select('fc_users.*', 'tf_bi_beneficiary_members.beneficiary_id')
            ->get();*/

        // decide if this nomination has maximunm consideration for approval
        /*if (count($nomination_committee_voters_for) < (count($beneficiary_committee_members)/2) && $request->decision == 'approved') {
            $err_mgs = 'Only ' . strval(count($nomination_committee_voters_for)) . ' out of ' . strval(count($beneficiary_committee_members)) . ' committee member(s) considered the approval of this Nomination, the average consideration(s) required for committee\'s approval must be greater or equals average of ' . strval((count($beneficiary_committee_members)/2)) . ' member(s) for this action to be completed.';
            return self::createJSONResponse("fail","error",[$err_mgs],200);
        } elseif (count($nomination_committee_voters_against) < (count($beneficiary_committee_members)/2) && $request->decision == 'declined') {
            $err_mgs = 'Only ' . strval(count($nomination_committee_voters_against)) . ' out of ' . strval(count($beneficiary_committee_members)) . ' committee member(s) supports declining this Nomination, the average consideration(s) required to decline must be greater or equals average of ' . strval((count($beneficiary_committee_members)/2)) . ' member(s) for this action to be completed.';
            return self::createJSONResponse("fail","error",[$err_mgs],200);
        }*/
        $nominationRequest->is_average_committee_members_check = 1;
        if ($request->decision == 'approved') {
            $nominationRequest->committee_head_checked_status = 'approved';
        } elseif ($request->decision == 'declined') {
            $nominationRequest->committee_head_checked_status = 'declined';
            $nominationRequest->status = 'declined';
        }
        $nominationRequest->committee_head_checked_comment = $request->comment;
        $nominationRequest->save();

        return $this->sendSuccess("Nomination Request Head of committee decision saved successfully");

    }

    // seleect nominee user by  email
    public function show_selected_email(NominationRequest $nominationRequest, $email) {
        $user = User::where('email', $email)->first();
        if (empty($user)) {
            return $this->sendResponse([], 'User not found');
        }

        $current_user = auth()->user();
        $beneficiary_members = BeneficiaryMember::where('beneficiary_user_id', $user->id)
                                ->orWhere('beneficiary_user_id', $current_user->id)
                                ->get();

        if (count($beneficiary_members) == 2) {
            return $this->sendResponse($user->toArray(), 'User retrieved successfully');
        }
        
        return $this->sendResponse([], 'User is not a beneficiary member');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * 
     * @param  \App\NominationRequest  $nominationRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(NominationRequest $nominationRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\NominationRequest  $nominationRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NominationRequest $nominationRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\NominationRequest  $nominationRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(NominationRequest $nominationRequest)
    {
        //
    }

    // general function processing forwarding of nomination details
    public function process_forward_details(Request $request, NominationRequest $nominationRequest, $id) {
        $nominationRequest = $nominationRequest->find($id);
        if(empty($nominationRequest)) {
            return $this->sendError("Oops... Nomination Request was not found");
        }

        $nominationRequest->{$request->column_to_update} = 1;
        $nominationRequest->save();

        return $this->sendSuccess("Nomination Request forwarded successfully");
    }

    // general function processing forwarding of all nomination details
    public function process_forward_all_details(Request $request, NominationRequest $nominationRequest, $itemIdType) {
        $nominationRequest = $nominationRequest->where('type', $itemIdType)
                ->where($request->column_to_update, 0)
                ->when(($request->column_to_update == 'is_desk_officer_check_after_average_committee_members_checked'), function ($query) {
                    return $query->where('is_average_committee_members_check', 1)
                                 ->where('committee_head_checked_status', 'approved');
                })
                ->update([$request->column_to_update => 1]);

        return $this->sendSuccess("All Nomination Request forwarded successfully");
    }

    // process approval decision by head of institution
    public function process_nomination_details_approval_by_hoi(Request $request, NominationRequest $nominationRequest, $id) {
        $nominationRequest = $nominationRequest->find($id);

        if(empty($nominationRequest)) {
            return $this->sendError("Oops... Nomination Request was not found");
        }
        
        // checking if any decision was made or selected
        $fields_err = [];
        $current_user = auth()->user();
        if (!($request->has('decision')) || $request->decision == 'undefined') {
            array_push($fields_err, "The Decision option must be selected.");
        }
        if (!($request->has('comment')) || $request->comment == null) {
            array_push($fields_err, "The Comment input is required.");
        }
        
        /*if (($request->has('comment')) && $request->comment != null && strlen($request->comment) < 10) {
            array_push($fields_err, "The Comment input must contain more than 10 characters.");
        }*/

        if (count($fields_err) > 0) {
            return self::createJSONResponse("fail","error",$fields_err,200);
        }

        //checking if user has role to vote
        if (!($current_user->hasRole('BI-head-of-institution'))) {
            return self::createJSONResponse("fail","error",["Current user doesn't has the role to make HOI approval decision"],200);
        }
        
        //checking committee member is from same institution
        $beneficiary_members = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();
        $nomination_request_name = strtoupper($nominationRequest->type).'Nomination';
        if ($nominationRequest->beneficiary_id != optional($beneficiary_members)->beneficiary_id) {
            $err_msg = "This " . $nomination_request_name . " Request doesn't belong to your Institution";
            return self::createJSONResponse("fail","error",[$err_msg],200);
        }

        $nominationRequest->is_head_of_institution_check = 1;
        $nominationRequest->head_of_institution_checked_status = strtolower($request->decision);
        $nominationRequest->head_of_institution_checked_comment = trim($request->comment);
        if (strtolower($request->decision) == 'declined') {
            $nominationRequest->status = 'declined';
        }
        $nominationRequest->save();

        return $this->sendSuccess("Nomination Request HOI approval decision saved successfully");
    }

    public function request_actions(Request $request, NominationRequest $nominationRequest, $id) {
        $nominationRequest = $nominationRequest->find($id);

        if(empty($nominationRequest)) {
            return $this->sendError("Oops... An unknown error when trying to " . $request->actionTasked . " Nomination Request");
        }

        if($request->actionTasked == 'decline'){
            $nominationRequest->status = 'declined';
        } elseif ($request->actionTasked == 'defer') {
            $nominationRequest->status = 'defered';
        } elseif ($request->actionTasked == 'approve') {
            $nominationRequest->status = 'approved';
        }

        $nominationRequest->save();
        
        return $this->sendResponse($request->all(), "Nomination Request has been " . $nominationRequest->status . " successfully");
    }
}
