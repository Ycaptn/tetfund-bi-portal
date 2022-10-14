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
        $aSTDNomination = $tETFundServer->storeUpdateAndDestroyDataInServer("tetfund-astd-api/a_s_t_d_nominations", $input);
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
    public function show(Request $request, $id, Organization $organization) {
        $inputs = $request->all();
        $inputs['id'] = $id;    /* append id to payload */
        $inputs['_method'] = 'GET';     /* append method to payload */
        $tETFundServer = new TETFundServer();   /*server class constructor */
        $data_retrival_response = $tETFundServer->storeUpdateAndDestroyDataInServer("tetfund-astd-api/a_s_t_d_nominations/$id", $inputs);
        return $data_retrival_response;
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
    public function update($id, Request $request, Organization $organization)
    {
        $inputs = $request->all();
        $tETFundServer = new TETFundServer();   /*server class constructor */
        $data_retrival_response = $tETFundServer->storeUpdateAndDestroyDataInServer("tetfund-astd-api/a_s_t_d_nominations/$id", $inputs);
        return $data_retrival_response;
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
    public function destroy(Request $request, $id, Organization $organization) {
        $inputs = $request->all();
        $inputs['id'] = $id; /* append id to payload */
        $tETFundServer = new TETFundServer(); /* class constructor */
        $data_response_on_delete = $tETFundServer->storeUpdateAndDestroyDataInServer("tetfund-astd-api/a_s_t_d_nominations/$id", $inputs);
        return $data_response_on_delete;
    }
}
