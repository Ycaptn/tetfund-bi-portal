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
use App\Http\Requests\API\CreateBeneficiaryMemberAPIRequest;

use Hasob\FoundationCore\Traits\ApiResponder;
use Hasob\FoundationCore\Models\Organization;
use DB;
use Hasob\FoundationCore\Controllers\BaseController as AppBaseController;
use App\Managers\TETFundServer;
use App\Http\Traits\BeneficiaryUserTrait;
use Hasob\FoundationCore\Models\User;
use Spatie\Permission\Models\Role;

/**
 * Class BeneficiaryController
 * @package App\Http\Controllers\API
 */

class BeneficiaryAPIController extends AppBaseController
{

    use ApiResponder;
    use BeneficiaryUserTrait;

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

    public function store_beneficiary_member(CreateBeneficiaryMemberAPIRequest $request, Organization $organization) {
        
        //get beneficiary
        $beneficiary = Beneficiary::find($request->beneficiary_id);

        $allRoles = Role::where('guard_name', 'web')->pluck('name');
        $selectedRoles = [];

        if (isset($allRoles) && count($allRoles) > 0) {
            foreach ($allRoles as $role) {
                if ($role == 'admin') continue;
                if (isset($role->{''.$role}) && $role->{''.$role} == 'on') {
                    array_push($selectedRoles, $role);
                }
            }
        }

        //new beneficiary staff payload
        $pay_load = [
            "email" => $request->bi_staff_email,
            "first_name" => ucwords($request->bi_staff_fname),
            "last_name" => ucwords($request->bi_staff_lname),
            "telephone" => $request->bi_telephone,
            'password' => 'password',
            "gender" => ucwords($request->bi_staff_gender),
            'organization_id' => $request->organization_id ?? null,
            'beneficiary_bi_id' => $beneficiary->id,
            'beneficiary_tetfund_iterum_id' => $beneficiary->tf_iterum_portal_key_id,
            'user_roles_arr' => $selectedRoles
        ];
        
        // creating beneficiary staff user to DB and on BIMS 
        $new_user_response = $this->create_new_bims_and_local_user($pay_load);

        if (isset($new_user_response['beneficiary_user_id']) && isset($new_user_response['beneficiary_user_email'])) {
            return $this->sendSuccess('New beneficiary User created successfully!'); 
        }

        return $this->sendError('An error occured while creating new beneficiary User');
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
        /* class constructor */
        $tETFundServer = new TETFundServer();
        $get_beneficiary_list = $tETFundServer->getBeneficiaryList();

        if (count($get_beneficiary_list) > 0) {
            foreach ($get_beneficiary_list as $key => $get_server_beneficiary) {
                $beneficiary_obj = Beneficiary::where([
                                        'tf_iterum_portal_key_id'=>$get_server_beneficiary->id,
                                        'official_email'=>$get_server_beneficiary->official_email
                                    ])->first();

                if(empty($beneficiary_obj)) {
                    $beneficiary_obj = new Beneficiary();
                }

                // set beneficiary properties
                $beneficiary_obj->organization_id = $get_server_beneficiary->organization_id;
                $beneficiary_obj->email = $get_server_beneficiary->email;
                $beneficiary_obj->full_name = $get_server_beneficiary->full_name;
                $beneficiary_obj->short_name = $get_server_beneficiary->short_name;
                $beneficiary_obj->official_email = $get_server_beneficiary->official_email;
                $beneficiary_obj->official_website = $get_server_beneficiary->official_website;
                $beneficiary_obj->type = $get_server_beneficiary->type;
                $beneficiary_obj->official_phone = $get_server_beneficiary->official_phone;
                $beneficiary_obj->address_street = $get_server_beneficiary->address_street;
                $beneficiary_obj->address_town = $get_server_beneficiary->address_town;
                $beneficiary_obj->address_state = $get_server_beneficiary->address_state;
                $beneficiary_obj->head_of_institution_title = $get_server_beneficiary->head_of_institution_title;
                $beneficiary_obj->geo_zone = $get_server_beneficiary->geo_zone;
                $beneficiary_obj->owner_agency_type = $get_server_beneficiary->owner_agency_type;
                $beneficiary_obj->tf_iterum_portal_key_id = $get_server_beneficiary->id;
               
                //create or update beneficiary institution
                $beneficiary_obj->save();

                //desk-officer custom email
                $desk_officer_email = strtolower($get_server_beneficiary->short_name)."@tetfund.gov.ng";
                
                //checking if beneficiary desk officer exist
                $beneficiary_desk_officer_user = User::where('email', $desk_officer_email)->first();
                
                if (empty($beneficiary_desk_officer_user)) {

                    // desk officer payload
                    $pay_load = [
                        "email" => $desk_officer_email,
                        'password' => 'password',
                        "telephone" => $get_server_beneficiary->official_phone,
                        "first_name" => strtoupper($get_server_beneficiary->short_name),
                        "last_name" => 'Desk-Officer',
                        'organization_id' => auth()->user()->organization_id,
                        //'organization_id' => $get_server_beneficiary->organization_id,
                        "gender" => 'Male',
                        'beneficiary_bi_id' => $beneficiary_obj->id,
                        'beneficiary_tetfund_iterum_id' => $get_server_beneficiary->id,
                        'beneficiary_synchronization' => true
                    ];
                    
                    // creating beneficiary desk officer
                    $beneficiary_desk_officer = $this->create_new_bims_and_local_user($pay_load);
                }
                
            }
        }

        return $this->sendSuccess('Beneficiary List successfully synchronized');

    }
}
