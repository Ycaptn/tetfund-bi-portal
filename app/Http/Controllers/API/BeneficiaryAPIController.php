<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateBeneficiaryAPIRequest;
use App\Http\Requests\API\CreateBeneficiaryMemberAPIRequest;
use App\Http\Requests\API\UpdateBeneficiaryAPIRequest;
use App\Http\Requests\API\UpdateBeneficiaryMemberAPIRequest;
use App\Http\Requests\API\UpdateBeneficiaryUserPasswordAPIRequest;
use App\Http\Traits\BeneficiaryUserTrait;
use App\Managers\TETFundServer;
use App\Models\Beneficiary;
use App\Models\BeneficiaryMember;
use Hasob\FoundationCore\Controllers\BaseController as AppBaseController;
use Hasob\FoundationCore\Models\Organization;
use Hasob\FoundationCore\Models\User;
use Hasob\FoundationCore\Traits\ApiResponder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

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
        $beneficiary = Beneficiary::find($id);

        if (empty($beneficiary)) {
            return $this->sendError("Beneficiary not found");

        }

        return $this->sendResponse($beneficiary->toArray(), "Benficiary retrieved succesfully");
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
    public function update(UpdateBeneficiaryAPIRequest $request, Organization $organization, $id)
    {
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
    public function store_beneficiary_member(CreateBeneficiaryMemberAPIRequest $request, Organization $organization)
    {
        //get beneficiary
        $beneficiary = Beneficiary::find($request->beneficiary_id);

        $allRoles = Role::where('guard_name', 'web')
            ->where('name', '!=', 'admin')
            ->where('name', 'like', 'bi-%')
            ->pluck('name');
        $selectedRoles = [];

        if (isset($allRoles) && count($allRoles) > 0) {
            foreach ($allRoles as $role) {
                if (isset($request->{'userRole_' . $role}) && $request->{'userRole_' . $role} == 'on') {
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
            'member_type' => $request->bi_member_type,
            'grade_level' => $request->bi_grade_level,
            'academic_member_level' => $request->bi_academic_member_level,
            'beneficiary_tetfund_iterum_id' => $beneficiary->tf_iterum_portal_key_id,
            'user_roles_arr' => $selectedRoles,
        ];

        // creating beneficiary staff user to DB and on BIMS
        $new_user_response = $this->create_new_bims_and_local_user($pay_load);

        if (isset($new_user_response['beneficiary_user_id']) && isset($new_user_response['beneficiary_user_email'])) {
            return $this->sendSuccess('New beneficiary User created successfully!');
        }

        return $this->sendError('An error occured while creating new beneficiary User');
    }

    /* specific beneficiary member detail */
    public function show_beneficiary_member($id, Organization $organization)
    {
        $beneficiary_member = User::find($id);

        if (empty($beneficiary_member)) {
            return $this->sendError('Beneficiary member is not found');
        }
        $member = BeneficiaryMember::where('beneficiary_user_id', $beneficiary_member->id)->first();
        $user_roles_arr = $beneficiary_member->roles()->pluck('name')->toArray();
        if (!empty($member)) {
            $beneficiary_member['member_type'] = $member->member_type;
            $beneficiary_member['grade_level'] = $member->grade_level;
            $beneficiary_member['academic_member_level'] = $member->academic_member_level;
        }
        $beneficiary_member['user_roles'] = (count($user_roles_arr) > 0) ? $user_roles_arr : '';
        return $this->sendResponse($beneficiary_member->toArray(), 'Beneficiary member retrieved successfully');
    }

    /* reset password for selected beneficiary member */
    public function reset_password_beneficiary_member($id, Organization $organization, UpdateBeneficiaryUserPasswordAPIRequest $request)
    {
        $beneficiary_member = User::find($id);

        if (empty($beneficiary_member)) {
            return $this->sendError('Beneficiary member is not found');
        }

        $beneficiary_member->password = bcrypt($request->password);
        $beneficiary_member->save();

        return $this->sendSuccess('Beneficiary member password reset successful');
    }

    /* update an existing beneficiary member */
    public function update_beneficiary_member(Organization $organization, UpdateBeneficiaryMemberAPIRequest $request, $id)
    {
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
                if (isset($request->{'userRole_' . $role}) && $request->{'userRole_' . $role} == 'on') {
                    array_push($selectedRoles, $role);
                }
            }
        }

        $member = BeneficiaryMember::where('beneficiary_user_id', $id)->first();
        if (!empty($member)) {
            $member->grade_level = $request->bi_grade_level;
            $member->member_type = $request->bi_member_type;
            $member->academic_member_level = $request->bi_academic_member_level;
            $member->save();
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
    public function enable_disable_beneficiary_member(Organization $org, $id)
    {
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
    public function delete_beneficiary_member(Organization $org, Request $request, $id)
    {
        $beneficiary_member = User::find($id);

        if (empty($beneficiary_member)) {
            return $this->sendError('Beneficiary member is not found');
        }

        //get beneficiary_to_member_mapping
        $beneficiary_to_member_mapping = BeneficiaryMember::where(['beneficiary_user_id' => $beneficiary_member->id, 'beneficiary_id' => $request->beneficiary_id])->first();
        if (!empty($beneficiary_to_member_mapping)) {
            $beneficiary_to_member_mapping->delete(); //delete in beneficiary member table
        }
        $beneficiary_member->delete(); //delete in users table

        return $this->sendSuccess('Beneficiary User data deleted successfully!');
    }

    /* sanitise string and remove invalid character formulating valid email prefix */
    public function sanitize_email_prefix($prefix_string)
    {
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
    public function synchronize_beneficiary_list(Organization $org, Request $request)
    {
        /* class constructor */
        $bi_users_emails_enroled = array();
        $tetFundServer = new TETFundServer();
        $get_beneficiary_list = $tetFundServer->getBeneficiaryList();

        if (count($get_beneficiary_list) > 0) {
            foreach ($get_beneficiary_list as $key => $get_server_beneficiary) {
                $beneficiary_obj = Beneficiary::where([
                    'tf_iterum_portal_key_id' => $get_server_beneficiary->id,
                    'official_email' => $get_server_beneficiary->official_email,
                ])->first();

                if (empty($beneficiary_obj)) {
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
                // $beneficiary_obj->save();

                //desk-officer custom email
                $email_prefix = $this->sanitize_email_prefix($get_server_beneficiary->short_name);
                $desk_officer_email = strtolower($email_prefix) . "@tetfund.gov.ng";

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

                    // $beneficiary_desk_officer_user->delete();

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
                                // $bi_user_response = $this->replicate_bi_user_to_bi_portal($beneficiary_member, $additional_payload);
                                array_push($bi_users_emails_enroled, $bi_user->email);

                            } elseif (!empty($bi_user_exist) && $bi_user_exist->deleted_at != null) {
                                $bi_user_exist->deleted_at = null;
                                // $bi_user_exist->save();
                            }
                        }
                    }
                }

            }
        }

        return $this->sendResponse($bi_users_emails_enroled, 'Beneficiary List successfully synchronized with (' . count($bi_users_emails_enroled) . ") User(s) replicated");

    }

    public function syncTFPortalBeneficiary(Request $request, Organization $org)
    {
        $beneficiary = Beneficiary::where([
            'tf_iterum_portal_key_id' => $request->id,
        ])->first();

        if (empty($beneficiary)) {
            $beneficiary = new Beneficiary();
        }

        // set beneficiary properties
        $beneficiary->organization_id = $org->id;
        $beneficiary->email = $request->email;
        $beneficiary->full_name = $request->full_name;
        $beneficiary->short_name = $request->short_name;
        $beneficiary->official_email = $request->official_email;
        $beneficiary->official_website = $request->official_website;
        $beneficiary->type = $request->type;
        $beneficiary->official_phone = $request->official_phone;
        $beneficiary->address_street = $request->address_street;
        $beneficiary->address_town = $request->address_town;
        $beneficiary->address_state = $request->address_state;
        $beneficiary->head_of_institution_title = $request->head_of_institution_title;
        $beneficiary->geo_zone = $request->geo_zone;
        $beneficiary->owner_agency_type = $request->owner_agency_type;
        $beneficiary->tf_iterum_portal_key_id = $request->id;
        $beneficiary->tf_iterum_portal_response_meta_data = json_encode($request->all());
        $beneficiary->tf_iterum_portal_response_at = date('Y-m-d H:i:s');
        //create or update beneficiary institution
        $beneficiary->save();

        return $this->sendResponse($beneficiary->toArray(), 'Beneficiary Synced Successfully');
    }


    public function processBulkBeneficiaryUsersUpload(Organization $org, Request $request) {
        $validator = Validator::make($request->all(), [
            'bulk_users_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $validator->setAttributeNames([
            'bulk_users_file' => 'Bulk Users CSV',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 200);
        }

        $current_user = auth()->user();
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();
        $beneficiary = optional($beneficiary_member)->beneficiary;
        $file = $request->file('bulk_users_file');

        $attachedFileName = strval(time()+1) . '.' . $request->bulk_users_file->getClientOriginalExtension();
        $request->bulk_users_file->move(public_path('uploads'), $attachedFileName);
        $path_to_file = public_path('uploads').'/'.$attachedFileName;

        //Process each line
        $loop = 1;
        $errors = [];
        $lines = file($path_to_file);
        $newly_created_users = [];

        if (count($lines) > 1) {
            foreach ($lines as $line) {
                
                if ($loop > 1) {
                    $data = explode(',', $line);
                    if (count($data) == 7) {
                        $data_3 = trim($data['3']);
                        $data_4 = strtolower(trim($data['4']));
                        $data_6 = strtolower(trim($data['6']));

                        //new beneficiary staff payload
                        $pay_load = [
                            "email" => $this->sanitize_email_prefix(trim($data['0'])),
                            "first_name" => ucwords(trim($data['1'])),
                            "last_name" => ucwords(trim($data['2'])),
                            "telephone" => is_numeric($data_3) ? $data_3 : null,
                            'password' => $this->generateStrongPassword(),
                            "gender" => in_array($data_4, ['male', 'female']) ? $data_4 : null,
                            'organization_id' => $org->id ?? null,
                            'beneficiary_bi_id' => $beneficiary->id,
                            'grade_level' => trim($data['5']),
                            'member_type' => in_array($data_6, ['academic', 'non-academic']) ? $data_6 : null,
                            'beneficiary_tetfund_iterum_id' => $beneficiary->tf_iterum_portal_key_id,
                            'user_roles_arr' => ['bi-staff'],
                        ];


                        $validator = Validator::make(['email' => $pay_load['email']], [
                            'email' => 'required|email',
                        ]);

                        if (!empty(User::where('email', $pay_load['email'])->first()) || $validator->fails()) {
                            continue;                        
                        }

                        // creating beneficiary staff user to DB and on BIMS
                        $new_user_response = $this->create_new_bims_and_local_user($pay_load);

                        if (isset($new_user_response['beneficiary_user_id']) && isset($new_user_response['beneficiary_user_email'])) {
                            array_push($newly_created_users, $new_user_response['beneficiary_user_email']);
                        }
                    }
                }
                $loop++;
            }
        }else{
            $errors[] = 'The uploaded csv file is empty';
        }
        
        if (count($errors) > 0) {
            return $this->sendError($this->array_flatten($errors), 'Errors processing file');
        }

        // Delete the bulk_users_file after processing
        File::delete($path_to_file);

        return $this->sendResponse($newly_created_users, 'Bulk upload completed successfully');
    }
}
