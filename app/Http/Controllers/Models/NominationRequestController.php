<?php

namespace App\Http\Controllers\Models;

use App\Models\NominationRequest;
use Hasob\FoundationCore\Controllers\BaseController;
use Hasob\FoundationCore\Models\Organization;

use Flash;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\BeneficiaryMember;
use App\Models\Beneficiary;
use \Hasob\FoundationCore\Models\User;
use App\Models\SubmissionRequest;
use App\Managers\TETFundServer;
use Hasob\FoundationCore\View\Components\CardDataView;

class NominationRequestController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Organization $org)
    {
        $current_user = Auth()->user();
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();
        $beneficiary = $beneficiary_member->beneficiary;

        $cdv_nomination_requests = new CardDataView(NominationRequest::class, "pages.nomination_requests.card_view_item");

        // set query parameters array
        $setDataQuery = ['organization_id'=>$org->id, 'beneficiary_id'=>$beneficiary_member->beneficiary_id];

        //filter if user is a staff
        if (auth()->user()->hasAnyRole(['BI-staff'])) {
           $setDataQuery['user_id'] = $current_user->id;
        }

        // nigerian states
        $nigerian_states = BaseController::statesList();

        $cdv_nomination_requests->setDataQuery($setDataQuery)
                        ->addDataGroup('All','deleted_at',null)
                        ->addDataGroup('CA','type','ca')
                        ->addDataGroup('TP','type','tp')
                        ->addDataGroup('TSAS','type','tsas')
                        ->enableSearch(true)
                        ->addDataOrder('created_at', 'DESC')
                        ->enablePagination(true)
                        ->setPaginationLimit(20)
                        ->setSearchPlaceholder('Search Nomination Request by type');

        if (request()->expectsJson()){
            return $cdv_nomination_requests->render();
        }

        // class constructor to fetch multiple data of countries and Institutions
        $tetFundServer = new TETFundServer();
        $countries_institutions_and_conferences = $tetFundServer->get_all_countries_institutions_and_conferences("tetfund-astd-api/from_country_get_countries_institutions_and_conferences", null);

        $countries = $countries_institutions_and_conferences->countries;  // fetch countries
        $institutions = $countries_institutions_and_conferences->institutions;  // fetch institutions
        $conferences = $countries_institutions_and_conferences->conferences;  // fetch conferences
        $world_institutions = NominationRequest::worldAcademicInstitutions();

        $all_beneficiary_users = User::join('tf_bi_beneficiary_members', 'fc_users.id', '=', 'tf_bi_beneficiary_members.beneficiary_user_id')
            ->where('tf_bi_beneficiary_members.beneficiary_id', $beneficiary_member->beneficiary_id)
            ->where('fc_users.id', '!=', $current_user->id)
            ->get(['email']);

        $bi_submission_requests = SubmissionRequest::where(['status'=>'not-submitted', 'type'=>'intervention'])->get(['id', 'title', 'intervention_year1', 'intervention_year2', 'intervention_year3', 'intervention_year4']);

        return view('pages.nomination_requests.card_view_index')
                ->with('countries', $countries)
                ->with('beneficiary', $beneficiary)
                ->with('conferences', $conferences)
                ->with('institutions', $institutions)
                ->with('current_user', $current_user)
                ->with('nigerian_states', $nigerian_states)
                ->with('world_institutions', $world_institutions)
                ->with('beneficiary_member', $beneficiary_member)
                ->with('all_beneficiary_users', $all_beneficiary_users)
                ->with('bi_submission_requests', $bi_submission_requests)
                ->with('cdv_nomination_requests', $cdv_nomination_requests);
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
    public function store(Organization $org, $id, Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\NominationRequest  $nominationRequest
     * @return \Illuminate\Http\Response
     */                                                                                                                         
    public function show(Organization $org, $id, Request $request)
    {
         /** @var NominationRequest $nominationRequest */
        $current_user = auth()->user();
        $nominationRequest = NominationRequest::find($id);

        if (empty($nominationRequest)) {
            //Flash::error('Submission Request not found');
            return redirect(route('tf-bi-portal.nomination_requests.index'));
        }

        $bi_beneficiary =  $nominationRequest->beneficiary;
        $bi_beneficiaries =  Beneficiary::all();
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $nominationRequest->user_id)->first();

        // nigerian states
        $nigerian_states = BaseController::statesList();

        // all possible attachements for this nomination
        $nomination_request_attachments = $nominationRequest->get_all_attachments($nominationRequest->id);

        // class constructor to fetch multiple data of countries and Institutions
        $tetFundServer = new TETFundServer();
        $countries_institutions_and_conferences = $tetFundServer->get_all_countries_institutions_and_conferences("tetfund-astd-api/from_country_get_countries_institutions_and_conferences", null);

        $countries = $countries_institutions_and_conferences->countries;  // fetch countries
        $institutions = $countries_institutions_and_conferences->institutions;  // fetch institutions
        $conferences = $countries_institutions_and_conferences->conferences;  // fetch conferences
        $world_institutions = $nominationRequest->worldAcademicInstitutions();
           
        return view('pages.nomination_requests.show')
            ->with('countries', $countries)
            ->with('conferences', $conferences)
            ->with('institutions', $institutions)
            ->with('current_user', $current_user)
            ->with('beneficiary', $bi_beneficiary)
            ->with('beneficiaries', $bi_beneficiaries)
            ->with('nigerian_states', $nigerian_states)
            ->with('nominationRequest', $nominationRequest)
            ->with('world_institutions', $world_institutions)
            ->with('beneficiary_member', $beneficiary_member)
            ->with('nomination_request_attachments', $nomination_request_attachments);
    }

    /**
     * Show the form for editing the specified resource.
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
}
