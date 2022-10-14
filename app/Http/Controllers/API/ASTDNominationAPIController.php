<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Hasob\FoundationCore\Traits\ApiResponder;
use Hasob\FoundationCore\Models\Organization;

use Hasob\FoundationCore\Controllers\BaseController as AppBaseController;
use App\Managers\TETFundServer;

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
    public function index(Request $request, Organization $organization)
    {
        $query = ASTDNomination::query();

        if ($request->get('skip')) {
            $query->skip($request->get('skip'));
        }
        if ($request->get('limit')) {
            $query->limit($request->get('limit'));
        }
        
        if ($organization != null){
            $query->where('organization_id', $organization->id)->where('type_of_nomination', 'ASTD');
        }

        $aSTDNominations = $this->showAll($query->get());

        return $this->sendResponse($aSTDNominations->toArray(), 'A S T D Nominations retrieved successfully');
    }

    /**
     * Store a newly created ASTDNomination in storage.
     * POST /aSTDNominations
     *
     * @param CreateASTDNominationAPIRequest $request
     *
     * @return Response
     */
    public function store(Request $request, Organization $organization)
    {
        $input = $request->all();
        /*class constructor*/
        $tETFundServer = new TETFundServer();
        $aSTDNomination = $tETFundServer->form_validation_data_response_from_server("tetfund-astd-api/a_s_t_d_nominations", $input);
        return $aSTDNomination;        
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
