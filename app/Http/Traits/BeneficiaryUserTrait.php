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
    public function create_new_bims_and_local_user($pay_load_data){
        $zUser = new User();
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
            $zUser->syncRoles(['bi-desk-officer']);
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

}
