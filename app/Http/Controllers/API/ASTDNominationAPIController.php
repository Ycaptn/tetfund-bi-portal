<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\ASTDNomination;

use App\Events\ASTDNominationCreated;
use App\Events\ASTDNominationUpdated;
use App\Events\ASTDNominationDeleted;

use App\Http\Requests\API\CreateASTDNominationAPIRequest;
use App\Http\Requests\API\UpdateASTDNominationAPIRequest;

use Hasob\FoundationCore\Traits\ApiResponder;
use Hasob\FoundationCore\Models\Organization;

use Hasob\FoundationCore\Controllers\BaseController as AppBaseController;

/**
 * Class ASTDNominationController
 * @package TETFund\ASTD\Controllers\API
 */

class ASTDNominationAPIController extends AppBaseController
{

    use ApiResponder;

    /**
     * Display a listing of the ASTDNomination.
     * GET|HEAD /aSTDNominations
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, Organization $org)
    {
        $current_user = Auth()->user();
        $cdv_a_s_t_d_nominations = new \Hasob\FoundationCore\View\Components\CardDataView(ASTDNomination::class, "pages.a_s_t_d_nominations.card_view_item");
        $cdv_a_s_t_d_nominations->setDataQuery(['organization_id'=>$org->id, 'type_of_nomination'=>'ASTD'])
                //->addDataGroup('label','field','value')
                //->addDataOrder('id','DESC')
                ->setSearchFields(['first_name','last_name'])
                ->addDataOrder('created_at','DESC')
                ->enableSearch(true)
                ->enablePagination(true)
                ->setPaginationLimit(20)
                ->setSearchPlaceholder('Search ASTDNomination By First or Last Name');

        if (request()->expectsJson()){
            return $cdv_a_s_t_d_nominations->render();
        }
        return $this->sendError("Couldn't retrieve any ASTD Nominations");
    }

    /**
     * Store a newly created ASTDNomination in storage.
     * POST /aSTDNominations
     *
     * @param CreateASTDNominationAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateASTDNominationAPIRequest $request, Organization $organization)
    {
        $input = $request->all();
        $input['type_of_nomination'] = 'ASTD';

        /** @var ASTDNomination $aSTDNomination */
        $aSTDNomination = ASTDNomination::create($input);
        
        ASTDNominationCreated::dispatch($aSTDNomination);
        return $this->sendResponse($aSTDNomination->toArray(), 'A S T D Nomination saved successfully');
    }

    /**
     * Display the specified ASTDNomination.
     * GET|HEAD /aSTDNominations/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id, Organization $organization)
    {
        /** @var ASTDNomination $aSTDNomination */
        $aSTDNomination = ASTDNomination::find($id);

        if (empty($aSTDNomination)) {
            return $this->sendError('A S T D Nomination not found');
        }

        $aSTDNomination->beneficiary = ($aSTDNomination->beneficiary_institution_id != null) ? $aSTDNomination->beneficiary : [];
        $aSTDNomination->institution = ($aSTDNomination->institution_id != null) ? $aSTDNomination->institution : [];
        $aSTDNomination->country = ($aSTDNomination->country_id != null) ? $aSTDNomination->country : [];
        $aSTDNomination->user = ($aSTDNomination->user_id != null) ? $aSTDNomination->user : [];

        return $this->sendResponse($aSTDNomination->toArray(), 'A S T D Nomination retrieved successfully');
    }

    /**
     * Update the specified ASTDNomination in storage.
     * PUT/PATCH /aSTDNominations/{id}
     *
     * @param int $id
     * @param UpdateASTDNominationAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateASTDNominationAPIRequest $request, Organization $organization)
    {
        /** @var ASTDNomination $aSTDNomination */
        $aSTDNomination = ASTDNomination::find($id);

        if (empty($aSTDNomination)) {
            return $this->sendError('A S T D Nomination not found');
        }

        $aSTDNomination->fill($request->all());
        $aSTDNomination->save();
        
        ASTDNominationUpdated::dispatch($aSTDNomination);
        return $this->sendResponse($aSTDNomination->toArray(), 'ASTDNomination updated successfully');
    }

    /**
     * Remove the specified ASTDNomination from storage.
     * DELETE /aSTDNominations/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id, Organization $organization)
    {
        /** @var ASTDNomination $aSTDNomination */
        $aSTDNomination = ASTDNomination::find($id);

        if (empty($aSTDNomination)) {
            return $this->sendError('A S T D Nomination not found');
        }

        $aSTDNomination->delete();
        ASTDNominationDeleted::dispatch($aSTDNomination);
        return $this->sendSuccess('A S T D Nomination deleted successfully');
    }
}
