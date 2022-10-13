<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Hasob\FoundationCore\Traits\ApiResponder;
use Hasob\FoundationCore\Models\Organization;

use Hasob\FoundationCore\Controllers\BaseController as AppBaseController;
use App\Managers\TETFundServer;

/**
 * Class InstitutionAPIController
 * @package App\Http\Controllers\API
 */

class InstitutionAPIController extends AppBaseController
{

    use ApiResponder;

    /**
     * Display a listing of the Institution.
     * GET|HEAD /institutions
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, Organization $organization)
    {
        /*class constructor*/
        $tETFundServer = new TETFundServer();
        $institutions = $tETFundServer->get_all_data_list_from_server("tetfund-astd-api/institutions", null);
        return $this->sendResponse($institutions, 'Institutions retrieved successfully');
    }

    /**
     * Store a newly created Institution in storage.
     * POST /institutions
     *
     * @param CreateInstitutionAPIRequest $request
     *
     * @return Response
     */
    public function store(Request $request, Organization $organization)
    {
        
    }

    /**
     * Display the specified Institution.
     * GET|HEAD /institutions/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id, Organization $organization)
    {
        
    }

    /**
     * Update the specified Institution in storage.
     * PUT/PATCH /institutions/{id}
     *
     * @param int $id
     * @param UpdateInstitutionAPIRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request, Organization $organization)
    {
       
    }

    /**
     * Remove the specified Institution from storage.
     * DELETE /institutions/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id, Organization $organization)
    {
        
    }
}
