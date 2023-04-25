<?php
namespace App\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

use Hasob\FoundationCore\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Models\BeneficiaryMember;
use App\Jobs\BeneficiaryUserCreatedJob;

trait BeneficiaryUserTrait {

    // create a new uer on bims and to users table
    public function create_new_bims_and_local_user($pay_load_data) {
        $zUser = new User();
        
        if (env('APP_ENV') != 'local') {
            if (env('BIMS_CLIENT_ID') && env('BIMS_IS_ENABLED') == true && env('BIMS_REGISTERATION_URI') != null) {
                //register user on bims
                $phone_number_raw = strval($pay_load_data['telephone']);
                $pay_load_data['telephone'] = (!empty($pay_load_data['telephone']) && $pay_load_data['telephone'] != null) ? preg_replace("/[^0-9]/", "", "$phone_number_raw" ) : '08011223344'; 
                $response = Http::acceptJson()->post(env('BIMS_REGISTERATION_URI'), [
                    "client_id" => env('BIMS_CLIENT_ID'),
                    "first_name" => $pay_load_data['first_name'],
                    "last_name" => $pay_load_data['last_name'],
                    "phone" => $pay_load_data['telephone'],
                    "email" => $pay_load_data['email'],
                    "gender" => ucfirst(substr($pay_load_data['gender'], 0, 1)),
                ]);          
            }
        }
        
            
        $zUser->telephone = ($pay_load_data['telephone'] != null) ? $pay_load_data['telephone'] : '08011223344';
        $zUser->email = $pay_load_data['email'];
        $zUser->first_name = $pay_load_data['first_name'];
        $zUser->last_name = $pay_load_data['last_name'];
        $zUser->organization_id = $pay_load_data['organization_id'];
        $zUser->password = bcrypt($pay_load_data['password']);
        if (isset($pay_load_data['user_roles_arr']) && count($pay_load_data['user_roles_arr']) > 0) {
            $zUser->syncRoles($pay_load_data['user_roles_arr']);
            $zUser->gender = $pay_load_data['gender'];
        } else if(isset($pay_load_data['beneficiary_synchronization']) && $pay_load_data['beneficiary_synchronization'] == true) {
            $zUser->syncRoles(['BI-desk-officer']);
        }
        $zUser->save();
        if (isset($zUser->id) && $zUser->id != null) {
            $response_arr = [
                'organization_id'=>$pay_load_data['organization_id'],
                'beneficiary_user_id'=>$zUser->id,
                'beneficiary_user_email'=>$zUser->email,
                'beneficiary_id'=>$pay_load_data['beneficiary_bi_id'],
                'beneficiary_tetfund_iterum_id'=>$pay_load_data['beneficiary_tetfund_iterum_id']
            ];

            // map new_beneficiary_desk_officer to beneficiary on beneficiary_member_table
            $mapping_beneficiary_desk_officer = BeneficiaryMember::create($response_arr);

            //trigger job to send mail to newly_created_user
            BeneficiaryUserCreatedJob::dispatch($zUser, $pay_load_data);

            // response to be returned
            return $response_arr;
        }
        return [];
    }


    public function replicate_bi_user_to_bi_portal($beneficiary_member, $additional_payload) {
        $user_record = $beneficiary_member->user ?? null;
        
        if (!empty($user_record) && $user_record != null) {
            $zUser = new User();

            $zUser->email = $user_record->email;
            $zUser->password = $user_record->password;
            $zUser->telephone = $user_record->telephone;
            $zUser->title = $user_record->title;
            $zUser->first_name = $user_record->first_name;
            $zUser->middle_name = $user_record->middle_name;
            $zUser->last_name = $user_record->last_name;
            $zUser->user_code = $user_record->user_code;
            $zUser->preferred_name = $user_record->preferred_name;
            $zUser->physical_location = $user_record->physical_location;
            $zUser->job_title = $user_record->job_title;
            $zUser->is_disabled = $user_record->is_disabled;
            $zUser->disable_reason = $user_record->disable_reason;
            $zUser->disabled_at = $user_record->disabled_at;
            $zUser->organization_id = auth()->user()->organization_id;
            $zUser->presence_status = $user_record->presence_status;
            $zUser->gender = $user_record->gender;
            $zUser->created_at = $user_record->created_at;
            $zUser->updated_at = $user_record->updated_at;

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

            if ($beneficiary_member->role!=null && $roles_arr[$beneficiary_member->role]) {
                $zUser->syncRoles([$roles_arr[$beneficiary_member->role]]);    
            } else {
                $zUser->syncRoles(['BI-staff']);
            }

            $zUser->save();

            // binding user to beneficiary membership table
            if (isset($zUser->id) && $zUser->id != null) {
                $response_arr = [
                    'organization_id' => $zUser->organization_id,
                    'beneficiary_user_id' => $zUser->id,
                    'beneficiary_user_email' => $zUser->email,
                    'beneficiary_id' => $additional_payload['beneficiary_bi_id'],
                    'beneficiary_tetfund_iterum_id' => $additional_payload['beneficiary_tetfund_iterum_id']
                ];

                // map beneficiary user to beneficiary on beneficiary_member_table
                $mapped_beneficiary_user = BeneficiaryMember::create($response_arr);

                // response to be returned
                return $mapped_beneficiary_user;
            }
                                
        }

        return null;
    }

    public function generateStrongPassword(){
        $specials = '!@#$%+';
        $numbers = '0123456789';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $all = $specials . $numbers . $lowercase . $uppercase;

        $password = '';
        $password .= $specials[rand(0, strlen($specials)-1)];
        $password .= $numbers[rand(0, strlen($numbers)-1)];
        $password .= $lowercase[rand(0, strlen($lowercase)-1)];
        $password .= $uppercase[rand(0, strlen($uppercase)-1)];

        $length = rand(2,4);
        for ($i=1; $i<=$length; ++$i) {
            $password .= $all[rand(0, strlen($all)-1)];
        }
        $password = str_shuffle($password);
        return $password;
    }

}
