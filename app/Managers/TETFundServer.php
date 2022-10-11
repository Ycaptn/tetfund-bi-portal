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
            return $api_response_data->data->token;
        }
    }
    
    public static function getInterventionChecklistData($intervention_id)
    {
        //TODO: perform operation, return return the requested object and success msg if ok, error otherwise
        return [];
    }

    public static function getFundAvailabilityData()
    {
        //TODO: perform operation, return return the requested object and success msg if ok, error otherwise
        return [];
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

    public static function getBeneficiaryList()
    {
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

    public static function processSubmissionRequest()
    {
        //TODO: perform operation, return return the requested object and success msg if ok, error otherwise
        return [];
    }

    public static function processSubmissionRecallRequest()
    {
        //TODO: perform operation, return return the requested object and success msg if ok, error otherwise
        return [];
    }

}