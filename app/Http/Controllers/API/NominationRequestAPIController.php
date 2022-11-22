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
use App\Http\Traits\BeneficiaryUserTrait;
use App\Models\BeneficiaryMember;
use App\Models\Beneficiary;
use App\Models\SubmissionRequest;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NominationRequestInviteNotification;

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
        if ($current_user->hasAnyRole(['bi-staff'])) {

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
                'user_roles_arr' => ['bi-staff']
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

        $nominee = $nominationRequest->user;    //user requesting nomination
        $nomination_committee_voters = (!empty($nominationRequest->nomination_committee_votes) ? array_column($nominationRequest->nomination_committee_votes->toArray(), 'user')  : [] );   //voters for nominee

        // possible roles allowed based on nomination
        $allowed_roles = [];
        if (strtolower($nominationRequest->type) == 'astd') {
            array_push($allowed_roles, 'bi-astd-commitee-head', 'bi-astd-commitee-member');
        } elseif (strtolower($nominationRequest->type) == 'tp') {
            array_push($allowed_roles, 'bi-tp-commitee-head', 'bi-tp-commitee-member');
        } elseif (strtolower($nominationRequest->type) == 'ca') {
            array_push($allowed_roles, 'bi-ca-commitee-head', 'bi-ca-commitee-member');
        } elseif (strtolower($nominationRequest->type) == 'tsas') {
            array_push($allowed_roles, 'bi-tsas-commitee-head', 'bi-tsas-commitee-member');
        }

        $beneficiary_committee_members = User::role($allowed_roles)
                                ->join('tf_bi_beneficiary_members', 'tf_bi_beneficiary_members.beneficiary_user_id', 'fc_users.id', )
                                ->where('tf_bi_beneficiary_members.beneficiary_id', $beneficiary_members->beneficiary_id)
                                ->select('fc_users.*', 'tf_bi_beneficiary_members.beneficiary_id')
                                ->get();

        $count_committee_members = count($beneficiary_committee_members);
        $count_committee_votes = count($nomination_committee_voters);

        $nominationRequestDetails = $nominationRequest->toArray();
        $nominationRequestDetails['nominee'] = $nominee;
        $nominationRequestDetails['beneficiary_committee_members'] = $beneficiary_committee_members;
        $nominationRequestDetails['nomination_committee_voters'] = $nomination_committee_voters;
        $nominationRequestDetails['count_committee_members'] = $count_committee_members;
        $nominationRequestDetails['count_committee_votes'] = $count_committee_votes;
        $nominationRequestDetails['nomination_request_type'] = $nominationRequest->type;

        return $this->sendResponse($nominationRequestDetails, 'Nomination Request details retrieved successfully');
    }


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

    public function process_forward_details(Request $request, NominationRequest $nominationRequest, $id) {
        $nominationRequest = $nominationRequest->find($id);
        if(empty($nominationRequest)) {
            return $this->sendError("Oops... Nomination Request was not found");
        }

        $nominationRequest->{$request->column_to_update} = 1;
        $nominationRequest->save();

        return $this->sendSuccess("Nomination Request forwarded successfully");
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
