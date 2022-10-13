<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Beneficiary;

use App\Events\BeneficiaryCreated;
use App\Events\BeneficiaryUpdated;
use App\Events\BeneficiaryDeleted;

use App\Http\Requests\API\CreateBeneficiaryAPIRequest;
use App\Http\Requests\API\UpdateBeneficiaryAPIRequest;

use Hasob\FoundationCore\Traits\ApiResponder;
use Hasob\FoundationCore\Models\Organization;
use DB;
use Hasob\FoundationCore\Controllers\BaseController as AppBaseController;
use App\Managers\TETFundServer;

/**
 * Class BeneficiaryController
 * @package App\Http\Controllers\API
 */

class BeneficiaryAPIController extends AppBaseController
{

    use ApiResponder;

    /**
     * Display a listing of the Beneficiary.
     * GET|HEAD /beneficiaries
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, Organization $organization)
    {
        $query = Beneficiary::query();

        if ($request->get('skip')) {
            $query->skip($request->get('skip'));
        }
        if ($request->get('limit')) {
            $query->limit($request->get('limit'));
        }
        
        /*if ($organization != null){
            $query->where('organization_id', $organization->id);
        }*/

        $beneficiaries = $this->showAll($query->get());
        return $this->sendResponse($beneficiaries->toArray(), 'Beneficiaries retrieved successfully');
    }

    /**
     * Store a newly created Beneficiary in storage.
     * POST /beneficiaries
     *
     * @param CreateBeneficiaryAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateBeneficiaryAPIRequest $request, Organization $organization)
    {
        
    }

    /**
     * Display the specified Beneficiary.
     * GET|HEAD /beneficiaries/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id, Organization $organization)
    {
        
    }

    /**
     * Update the specified Beneficiary in storage.
     * PUT/PATCH /beneficiaries/{id}
     *
     * @param int $id
     * @param UpdateBeneficiaryAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBeneficiaryAPIRequest $request, Organization $organization)
    {
        
    }

    /**
     * Remove the specified Beneficiary from storage.
     * DELETE /beneficiaries/{id}
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

    public function synchronize_beneficiary_list(Organization $org, Request $request){
        /*class constructor*/
        $tETFundServer = new TETFundServer();
        $get_beneficiary_list = $tETFundServer->getBeneficiaryList();

        if (count($get_beneficiary_list) > 0) {
            DB::table('tf_bi_portal_beneficiaries')->delete();    /*delete old records*/
            foreach ($get_beneficiary_list as $key => $get_server_beneficiary) {
                $beneficiary_obj = new Beneficiary();   //beneficiary Object
               
                $beneficiary_obj->organization_id = $get_server_beneficiary->organization_id;
                $beneficiary_obj->email = $get_server_beneficiary->email;
                $beneficiary_obj->full_name = $get_server_beneficiary->full_name;
                $beneficiary_obj->short_name = $get_server_beneficiary->short_name;
                $beneficiary_obj->official_email = $get_server_beneficiary->official_email;
                $beneficiary_obj->official_website = $get_server_beneficiary->official_website;
                $beneficiary_obj->official_phone = $get_server_beneficiary->official_phone;
                $beneficiary_obj->address_street = $get_server_beneficiary->address_street;
                $beneficiary_obj->address_town = $get_server_beneficiary->address_town;
                $beneficiary_obj->address_state = $get_server_beneficiary->address_state;
                $beneficiary_obj->head_of_institution_title = $get_server_beneficiary->head_of_institution_title;
                $beneficiary_obj->geo_zone = $get_server_beneficiary->geo_zone;
                $beneficiary_obj->owner_agency_type = $get_server_beneficiary->owner_agency_type;
                $beneficiary_obj->tf_iterum_portal_key_id = $get_server_beneficiary->id;
                
                $beneficiary_obj->save();
            }
        }
        
        return $this->sendResponse([], 'Beneficiary List successfully synchronized');
    }
}
