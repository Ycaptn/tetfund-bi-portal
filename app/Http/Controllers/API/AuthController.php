<?php

namespace App\Http\Controllers\API;


use Response;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Beneficiary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;

use App\Http\Requests\API\LoginAPIRequest;
use App\Http\Requests\API\PasswordResetAPIRequest;

use Hasob\FoundationCore\Models\User;
use Hasob\FoundationCore\Models\Organization;

use App\Http\Controllers\AppBaseController;


class AuthController extends AppBaseController
{

    public function login(Organization $organization, LoginAPIRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user == null || !Hash::check($request->password, $user->password)) {
            return $this->sendError([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (Auth::attempt(['email'=>$request->email, 'password'=>$request->password])) {

            $request->session()->regenerate();
            return $this->sendResponse([
                 'token' => $user->createToken('API Token')->plainTextToken, 
                 'profile' =>$user,
                 'role' => Auth::user()->getRoleNames() 
                ], 'Login successful'
            );
        } 
        
    }

    public function reset(Organization $organization, PasswordResetAPIRequest $request)
    {

        $user = User::where('email', $request->email)->first();
        if ($user == null || !Hash::check($request->password, $user->password)) {
            return $this->sendError([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return $this->sendResponse(null,'Reset successful');
    }

    public function resetPassword(Organization $organization, PasswordResetAPIRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->sendError('User not found');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return $this->sendResponse(null,'Reset successful');
    }

    public function recoverPassword(Organization $organization, PasswordResetAPIRequest $request)
    {

        $user = User::where('email', $request->email)->first();
        if ($user == null) {
            return $this->sendError([
                'email' => ['The email address is not correct.'],
            ]);
        }

        $token = \Illuminate\Support\Str::random(10);
        $user->sendPasswordResetNotification($token);

        return $this->sendResponse(null,'Reset successful');
    }

    public function logout(Organization $organization, Request $request)
    {

        //Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $this->sendResponse(
            null, 
            'Logout successful'
        );
    }

    public function index(Organization $organization, Request $request)
    {
        $query = User::query();

        if ($request->get('skip')) {
            $query->skip($request->get('skip'));
        }
        if ($request->get('limit')) {
            $query->limit($request->get('limit'));
        }
        
        if ($organization != null){
            $query->where('organization_id', $organization->id);
        }

        $users = $query->get();

        return $this->sendResponse($users->toArray(), 'Users retrieved successfully');
    }

    public function fetchUser(Organization $organization, $id)
    {
        $user = User::find($id);

        if (!$user) {
           return $this->sendError('User not found');
        }

        return $this->sendResponse($user->toArray(), 'User retrieved successfully');
    }

    public function profile(Organization $organization, Request $request)
    {
        return $this->sendResponse(
            ['user' => $request->user], 
            'User Profile 2 found successfully'
        );
    }

    public function sycUserRecord(Organization $org, Request $request) {
        if (!$request->has('email')) {
            return $this->sendError('User email must be provided.');
        }

        if ($request->has('password') && empty($request->input('password'))) {
            return $this->sendError('Password cannot be blank.');
        }

        $is_new_user = false;
        $user = User::where('email', $request->get('email'))->first();

        if ($user==null) {
            $user = new User();
            $is_new_user = true;
        }

        $user_data = $request->except(['password']);
        $user_data['organization_id'] = $org->id;

        $roles_arr = [
            'bi_admin' => 'BI-admin',
            'bi_deskofficer' => 'BI-desk-officer',
            'bi_hoi' => 'BI-head-of-institution',
            'bi_ict' => 'BI-ict',
            'bi_librarian' => 'BI-librarian',
            'bi_works' => 'BI-works',
            'bi_pp_director' => 'BI-physical-planning-dept',
            'bi_staff' => 'BI-staff',
            'bi_student' => 'BI-student',
        ];

        if ($request->has('role') && $roles_arr[$request->input('role')]) {
            $user->syncRoles([$roles_arr[$request->input('role')]]);    
        }

        if ($request->has('password')) {
            $user_data['password'] = bcrypt($request->input('password'));
        }
        
        if ($is_new_user==false) {
            $user->update($user_data);
        } else {
            $user->create($user_data);
        }
        
        $beneficiary = Beneficiary::where([
            'tf_iterum_portal_key_id' => $request->beneficiary_id,
        ])->first();

        if(!empty($beneficiary)){
           $member = \App\Models\BeneficiaryMember::firstOrNew(['beneficiary_user_id' => $user->id,'beneficiary_id' => $beneficiary->id]);
           $member->organization_id = $org->id;
           $member->beneficiary_tetfund_iterum_id = $beneficiary->tf_iterum_portal_key_id;
           $member->save();
        }
        
        return $this->sendResponse($user_data, "User record successfully sync.");
    }

}
