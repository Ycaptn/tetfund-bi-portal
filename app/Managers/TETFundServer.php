<?php

namespace App\Managers;

use Hash;
use Response;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

use Hasob\FoundationCore\Models\User;
use Hasob\FoundationCore\Models\Department;
use Hasob\FoundationCore\Models\Organization;


class TETFundServer {


    public function __construct(){
    }

    private static $authenticated_user_id;
    private static $authenticated_user_organization_id;

    private static function setup_curl($token, $tetfund_server_url, $post_body=null){

        $bearer_auth_token = $token;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tetfund_server_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER,[
            "Authorization: Bearer {$bearer_auth_token}",
            "accept: application/json",
            "cache-control: no-cache",
            'Content-Type:application/json'
        ]);

        if ($post_body!=null){
            $payload = json_encode($post_body);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload );
            curl_setopt($ch, CURLOPT_POST, 1);
        }

        return $ch;
    }

    private static function get_auth_token(){

        $server_api_url = Config::get('keys.tetfund.server_api_url');
        $server_api_user = Config::get('keys.tetfund.server_api_user');
        $server_api_pwd = Config::get('keys.tetfund.server_api_pwd');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "{$server_api_url}/login");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER,[
            "accept: application/json",
            "cache-control: no-cache",
            'Content-Type:application/json'
        ]);

        $payload = json_encode([
            'email'=>"{$server_api_user}",
            'password'=>"{$server_api_pwd}"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_POST, 1);

        $api_response = curl_exec($ch);
        $api_response_data = json_decode($api_response);
        curl_close($ch);

        if ($api_response != null && $api_response_data !=null && $api_response_data->data!=null && $api_response_data->data->token!=null){

            /*set authenticated user_id*/
            self::$authenticated_user_id = (isset($api_response_data->data->profile->id)) ? $api_response_data->data->profile->id : null;

            /*set authenticated user organization_id*/
            self::$authenticated_user_organization_id = (isset($api_response_data->data->profile->organization_id)) ? $api_response_data->data->profile->organization_id : null;
            
            return $api_response_data->data->token;
        }
    }
    
    public static function getInterventionChecklistData($intervention_id) {
        $server_api_url = Config::get('keys.tetfund.server_api_url');
        $token = self::get_auth_token();
        $existing_model_artifact = self::setup_curl($token, "{$server_api_url}/tetfund-bi-submission-api/intervention-checklist/$intervention_id", null);
        $api_response = curl_exec($existing_model_artifact);
        $api_response_data = json_decode($api_response);
        curl_close ($existing_model_artifact);
        return ($api_response != null && $api_response_data !=null && is_array($api_response_data->data)) ?  $api_response_data->data : [];
    }

    public static function getFundAvailabilityData($beneficiary_id, $tf_iterum_intervention_line_key_id, $years=null, $allocation_details=false) {
        $server_api_url = Config::get('keys.tetfund.server_api_url');
        $token = self::get_auth_token();
        $pay_load = [
            '_method' => 'GET',
            'beneficiary_id' => $beneficiary_id,
            'years' => $years,
            'tf_iterum_intervention_line_key_id' => $tf_iterum_intervention_line_key_id
        ];

        if ($allocation_details==true) {
            $pay_load['allocation_details'] = true;
        }

        $funds_available = self::setup_curl($token, "{$server_api_url}/tetfund-bi-submission-api/fund-availability/{$beneficiary_id}", $pay_load);
        $api_response = curl_exec($funds_available);
        $api_response_data = json_decode($api_response);

        curl_close ($funds_available);

        // return server error response when processing submissions containing valid intervention line
        if (isset($api_response_data->success) && $api_response_data->success == false && isset($tf_iterum_intervention_line_key_id) && $tf_iterum_intervention_line_key_id != null ) {
            return $api_response_data;
        }

        // return server success and default response message
        return ($api_response != null && $api_response_data !=null && isset($api_response_data->data)) ?  $api_response_data->data : [];
    }

    public static function getSubmissionRequestData($submission_id)
    {
        //TODO: perform operation, return return the requested object and success msg if ok, error otherwise
        return [];
    }

    public static function getInterventionStatusData()
    {
        //TODO: perform operation, return return the requested object and success msg if ok, error otherwise
        return [];
    }

    public static function getBeneficiaryCommunicationData()
    {
        //TODO: perform operation, return return the requested object and success msg if ok, error otherwise
        return [];
    }

    public static function getBeneficiaryList() {
        $server_api_url = Config::get('keys.tetfund.server_api_url');

        $token = self::get_auth_token();
        $ch = self::setup_curl($token, "{$server_api_url}/tetfund-bi-submission-api/beneficiary-list", null);

        $api_response = curl_exec($ch);
        $api_response_data = json_decode($api_response);
        curl_close ($ch);

        if ($api_response != null && $api_response_data !=null && is_array($api_response_data->data)){
            return $api_response_data->data;
        }

        return [];
    }

    public static function getBeneficiaryData($beneficiary_id)
    {
        //TODO: perform operation, return return the requested object and success msg if ok, error otherwise
        return [];
    }

    public static function getBeneficiaryUsersData($beneficiary_id)
    {
        //TODO: perform operation, return return the requested object and success msg if ok, error otherwise
        return [];
    }

    public static function processSubmissionRequest($pay_load, $tf_beneficiary_id) {
        $server_api_url = Config::get('keys.tetfund.server_api_url');
        $token = self::get_auth_token();

         /* append user_id to payload */
        $pay_load['user_id'] = self::$authenticated_user_id;

        /* append organization_id to payload */
        $pay_load['organization_id'] = self::$authenticated_user_organization_id; 

        $ch = self::setup_curl($token, "{$server_api_url}/tetfund-bi-submission-api/process-submission-request/{$tf_beneficiary_id}", $pay_load);
        $api_response = curl_exec($ch);
        $api_response_data = json_decode($api_response);

        curl_close ($ch);
        return $api_response_data;
    }

    public static function processSubmissionRecallRequest()
    {
        //TODO: perform operation, return return the requested object and success msg if ok, error otherwise
        return [];
    }

    public static function get_all_data_list_from_server($endpoint_path, $pay_load) {
        $server_api_url = Config::get('keys.tetfund.server_api_url');
        $token = self::get_auth_token();
        $ch = self::setup_curl($token, "{$server_api_url}/$endpoint_path", $pay_load);
        $api_response = curl_exec($ch);
        $api_response_data = json_decode($api_response);
        curl_close ($ch);
        return ($api_response != null && $api_response_data !=null && is_array($api_response_data->data)) ?  $api_response_data->data : [];
    }

    public static function get_row_records_from_server($endpoint_path, $pay_load) {
        $server_api_url = Config::get('keys.tetfund.server_api_url');
        $token = self::get_auth_token();
        $ch = self::setup_curl($token, "{$server_api_url}/$endpoint_path", $pay_load);
        $api_response = curl_exec($ch);
        $api_response_data = json_decode($api_response);
        curl_close ($ch);
        return ($api_response != null && $api_response_data !=null && isset($api_response_data->data)) ?  $api_response_data->data : [];
    }

}