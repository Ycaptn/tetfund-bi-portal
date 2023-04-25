<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Beneficiary;
use App\Models\BeneficiaryMember;

use App\Events\BeneficiaryCreated;
use App\Events\BeneficiaryUpdated;
use App\Events\BeneficiaryDeleted;

use App\Http\Requests\API\CreateBeneficiaryAPIRequest;
use App\Http\Requests\API\UpdateBeneficiaryAPIRequest;
use App\Http\Requests\API\CreateBeneficiaryMemberAPIRequest;
use App\Http\Requests\API\UpdateBeneficiaryMemberAPIRequest;
use App\Http\Requests\API\UpdateBeneficiaryUserPasswordAPIRequest;

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
    public function update(UpdateBeneficiaryAPIRequest $request, Organization $organization, $id) {
        $beneficiary = Beneficiary::find($id);

        if (empty($beneficiary)) {
            return $this->sendError('Beneficiary was not found.');
        }

        $pay_load = $request->all();
        $pay_load['_method'] = 'PUT';
        $pay_load['type'] = $beneficiary->type;
        $pay_load['full_name'] = $beneficiary->full_name;
        $pay_load['id'] = $beneficiary->tf_iterum_portal_key_id;
        $pay_load['official_email'] = $beneficiary->official_email;

        // update beneficiary record in iterum server
        $tetFundServer = new TETFundServer();
        $set_beneficiary_detail = $tetFundServer->updateBeneficiaryData($pay_load);

        // update beneficiary record in bi server
        $beneficiary->update($request->all());
        
        return $this->sendSuccess('Beneficiary Data Successfully Updated!');
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

    /* to store a new beneficiary member */
    public function store_beneficiary_member(CreateBeneficiaryMemberAPIRequest $request, Organization $organization) {
        //get beneficiary
        $beneficiary = Beneficiary::find($request->beneficiary_id);

        $allRoles = Role::where('guard_name', 'web')
                        ->where('name', '!=', 'admin')
                        ->where('name', 'like', 'bi-%')
                        ->pluck('name');
        $selectedRoles = [];

        if (isset($allRoles) && count($allRoles) > 0) {
            foreach ($allRoles as $role) {
                if (isset($request->{'userRole_'.$role}) && $request->{'userRole_'.$role} == 'on') {
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
            'password' => $this->generateStrongPassword(),
            "gender" => strtolower($request->bi_staff_gender),
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

    /* specific beneficiary member detail */
    public function show_beneficiary_member($id, Organization $organization) {
        $beneficiary_member = User::find($id);
        
        if (empty($beneficiary_member)) {
            return $this->sendError('Beneficiary member is not found');
        }

        $user_roles_arr = $beneficiary_member->roles()->pluck('name')->toArray();

        $beneficiary_member['user_roles'] = (count($user_roles_arr) > 0) ? $user_roles_arr : '';
        return $this->sendResponse($beneficiary_member->toArray(), 'Beneficiary member retrieved successfully');
    }

    /* reset password for selected beneficiary member */
    public function reset_password_beneficiary_member($id, Organization $organization, UpdateBeneficiaryUserPasswordAPIRequest $request) {
        $beneficiary_member = User::find($id);
        
        if (empty($beneficiary_member)) {
            return $this->sendError('Beneficiary member is not found');
        }

        $beneficiary_member->password = bcrypt($request->password);
        $beneficiary_member->save();
        
        return $this->sendSuccess('Beneficiary member password reset successful');
    }

    /* update an existing beneficiary member */
    public function update_beneficiary_member(Organization $organization, UpdateBeneficiaryMemberAPIRequest $request, $id) {
        $beneficiary_member = User::find($id);

        if (empty($beneficiary_member)) {
            return $this->sendError('Beneficiary member is not found');
        }

        $allRoles = Role::where('guard_name', 'web')
                        ->where('name', '!=', 'admin')
                        ->where('name', 'like', 'bi-%')
                        ->pluck('name');
        $selectedRoles = [];

        if (isset($allRoles) && count($allRoles) > 0) {
            foreach ($allRoles as $role) {
                if (isset($request->{'userRole_'.$role}) && $request->{'userRole_'.$role} == 'on') {
                    array_push($selectedRoles, $role);
                }
            }
        }

        //update beneficiary user details
        $beneficiary_member->email = $request->bi_staff_email;
        $beneficiary_member->first_name = $request->bi_staff_fname;
        $beneficiary_member->last_name = $request->bi_staff_lname;
        $beneficiary_member->telephone = $request->bi_telephone;
        $beneficiary_member->gender = $request->bi_staff_gender;
        $beneficiary_member->syncRoles($selectedRoles);
        $beneficiary_member->save(); /* save to DB */

        return $this->sendSuccess('Beneficiary User updated successfully!'); 
    }


    /* disable beneficiaty member */
    public function enable_disable_beneficiary_member (Organization $org, $id) {
        $beneficiary_member = User::find(substr($id, 0, -1));
        
        if (empty($beneficiary_member)) {
            return $this->sendError('Beneficiary member is not found');
        }
        
        if ($beneficiary_member->is_disabled == 1) {
            $flag_response = "enabled";
            $beneficiary_member->is_disabled = 0;
        } else {
            $flag_response = "disabled";
            $beneficiary_member->is_disabled = 1;
        }

        $beneficiary_member->save();
        return $this->sendSuccess("Beneficiary User $flag_response successfully!");
    }

    /* delete beneficiary member */
    public function delete_beneficiary_member(Organization $org, Request $request, $id) {
        $beneficiary_member = User::find($id);
        
        if (empty($beneficiary_member)) {
            return $this->sendError('Beneficiary member is not found');
        }

        //get beneficiary_to_member_mapping
        $beneficiary_to_member_mapping = BeneficiaryMember::where(['beneficiary_user_id'=>$beneficiary_member->id, 'beneficiary_id'=>$request->beneficiary_id])->first();
        if (!empty($beneficiary_to_member_mapping)) {
            $beneficiary_to_member_mapping->delete(); //delete in beneficiary member table
        }
        $beneficiary_member->delete(); //delete in users table

        return $this->sendSuccess('Beneficiary User data deleted successfully!'); 
    }

    /* sanitise string and remove invalid character formulating valid email prefix */
    public function sanitize_email_prefix($prefix_string) {
        $valid_prefix = trim($prefix_string);
        $valid_prefix = str_replace(' ', '-', $valid_prefix);
        $valid_prefix = str_replace('(', '', $valid_prefix);
        $valid_prefix = str_replace(')', '', $valid_prefix);
        $valid_prefix = str_replace("'", '', $valid_prefix);
        $valid_prefix = str_replace('`', '', $valid_prefix);
        $valid_prefix = str_replace('"', '', $valid_prefix);

        return $valid_prefix;
    }

    /* sychronization fuction */
    public function synchronize_beneficiary_list(Organization $org, Request $request) {
        /* class constructor */
        $bi_users_emails_enroled = array();
        $tetFundServer = new TETFundServer();
        $get_beneficiary_list = $tetFundServer->getBeneficiaryList();

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
                $beneficiary_obj->tf_iterum_portal_response_meta_data = json_encode($get_server_beneficiary);
                $beneficiary_obj->tf_iterum_portal_response_at = date('Y-m-d H:i:s');
               
                //create or update beneficiary institution
                $beneficiary_obj->save();

                //desk-officer custom email
                $email_prefix = $this->sanitize_email_prefix($get_server_beneficiary->short_name);
                $desk_officer_email = strtolower($email_prefix)."@tetfund.gov.ng";
                
                //checking if beneficiary desk officer exist
                $beneficiary_desk_officer_user = User::where('email', $desk_officer_email)->first();
                
                if (!empty($beneficiary_desk_officer_user)) {

                    // desk officer payload
                    // $pay_load = [
                    //     "email" => $desk_officer_email,
                    //     'password' => $this->generateStrongPassword(),
                    //     "telephone" => $get_server_beneficiary->official_phone,
                    //     "first_name" => strtoupper($get_server_beneficiary->short_name),
                    //     "last_name" => 'Desk-Officer',
                    //     'organization_id' => auth()->user()->organization_id,
                    //     //'organization_id' => $get_server_beneficiary->organization_id,
                    //     "gender" => 'male',
                    //     'beneficiary_bi_id' => $beneficiary_obj->id,
                    //     'beneficiary_tetfund_iterum_id' => $get_server_beneficiary->id,
                    //     'beneficiary_synchronization' => true
                    // ];
                    
                    $beneficiary_desk_officer_user->delete();

                    // creating beneficiary desk officer
                    // $beneficiary_desk_officer = $this->create_new_bims_and_local_user($pay_load);
                }

                // replication and update records for beneficiary members
                if (isset($get_server_beneficiary->memberships) && count($get_server_beneficiary->memberships) > 0) {
                    foreach ($get_server_beneficiary->memberships as $beneficiary_member) {
                        if (isset($beneficiary_member->user)) {

                            $bi_user = $beneficiary_member->user;

                            //checking if beneficiary user exist
                            $bi_user_exist = User::where('email', $bi_user->email)->withTrashed()->first();
                            
                            // create user if user does not exist on BI portal
                            if (empty($bi_user_exist) || $bi_user_exist == null) {

                                $additional_payload = [
                                    'beneficiary_bi_id' => $beneficiary_obj->id,
                                    'beneficiary_tetfund_iterum_id' => $get_server_beneficiary->id,
                                ];

                                // creating beneficiary desk officer
                                $bi_user_response = $this->replicate_bi_user_to_bi_portal($beneficiary_member, $additional_payload);
                                array_push($bi_users_emails_enroled, $bi_user->email);
                            }
                        }
                    }
                }
                
            }
        }

        return $this->sendResponse($bi_users_emails_enroled, 'Beneficiary List successfully synchronized with ('.count($bi_users_emails_enroled).") User(s) replicated");

    }
}
