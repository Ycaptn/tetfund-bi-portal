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


class BIMSTETFundServer {


    public function __construct(){
    }

    private static function setup_curl($bims_tetfund_url, $post_body=null){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $bims_tetfund_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER,[
            "accept: application/json",
            'Content-Type:application/json'
        ]);

        if ($post_body!=null){
            $payload = json_encode($post_body);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload );
            curl_setopt($ch, CURLOPT_POST, 1);
        }

        return $ch;
    }

    public static function getInstitionsList() {
        $bims_api_base_url = env('BIMS_API_BASE_URL', 'https://bims.tetfund.gov.ng/api');
        $ch = self::setup_curl("{$bims_api_base_url}/institutions", null);
        $api_response = curl_exec($ch);
        $api_response_data = json_decode($api_response);
        curl_close ($ch);
        return ($api_response != null && $api_response_data !=null && is_array($api_response_data->data)) ? $api_response_data->data : [];
    }
    
}