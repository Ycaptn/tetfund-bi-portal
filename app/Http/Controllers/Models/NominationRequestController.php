<?php

namespace App\Http\Controllers\Models;

use App\Models\NominationRequest;
use Hasob\FoundationCore\Controllers\BaseController;
use Hasob\FoundationCore\Models\Organization;

use Flash;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\BeneficiaryMember;
use \Hasob\FoundationCore\Models\User;

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

        $cdv_nomination_requests = new \Hasob\FoundationCore\View\Components\CardDataView(NominationRequest::class, "pages.nomination_requests.card_view_item");

        // set query parameters array
        $setDataQuery = ['organization_id'=>$org->id, 'beneficiary_id'=>$beneficiary_member->beneficiary_id];

        //filter if user is a staff
        if (auth()->user()->hasAnyRole(['bi-staff'])) {
           $setDataQuery['user_id'] = $current_user->id;
        }

        $cdv_nomination_requests->setDataQuery($setDataQuery)
                        ->addDataGroup('All','deleted_at',null)
                        ->addDataGroup('ASTD','type','astd')
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

        $all_beneficiary_users = User::join('tf_bi_beneficiary_members', 'fc_users.id', '=', 'tf_bi_beneficiary_members.beneficiary_user_id')
            ->where('tf_bi_beneficiary_members.beneficiary_id', $beneficiary_member->beneficiary_id)
            ->where('fc_users.id', '!=', $current_user->id)
            ->get(['email']);

        return view('pages.nomination_requests.card_view_index')
                ->with('current_user', $current_user)
                ->with('months_list', BaseController::monthsList())
                ->with('states_list', BaseController::statesList())
                ->with('all_beneficiary_users', $all_beneficiary_users)
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

        return view('pages.nomination_requests.show')
            ->with('nominationRequest', $nominationRequest)
            ->with('beneficiary', $bi_beneficiary);
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
