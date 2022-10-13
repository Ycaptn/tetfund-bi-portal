<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Hasob\FoundationCore\Traits\ApiResponder;
use Hasob\FoundationCore\Models\Organization;

use Hasob\FoundationCore\Controllers\BaseController as AppBaseController;
use App\Managers\TETFundServer;

/**
 * Class CountryAPIController
 * @package App\Http\Controllers\API
 */

class CountryAPIController extends AppBaseController
{

    use ApiResponder;

    /**
     * Display a listing of the Country.
     * GET|HEAD /countries
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, Organization $organization)
    {
         /*class constructor*/
        $tETFundServer = new TETFundServer();
        $countries = $tETFundServer->get_all_data_list_from_server("tetfund-astd-api/countries", null);
        return $this->sendResponse($countries, 'Countries retrieved successfully');
    }

    /**
     * Store a newly created Country in storage.
     * POST /countries
     *
     * @param CreateCountryAPIRequest $request
     *
     * @return Response
     */
    public function store(Request $request, Organization $organization)
    {
        
    }

    /**
     * Display the specified Country.
     * GET|HEAD /countries/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id, Organization $organization)
    {
       
    }

    /**
     * Update the specified Country in storage.
     * PUT/PATCH /countries/{id}
     *
     * @param int $id
     * @param UpdateCountryAPIRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request, Organization $organization)
    {
    
    }

    /**
     * Remove the specified Country from storage.
     * DELETE /countries/{id}
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
