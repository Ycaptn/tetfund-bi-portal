<?php

namespace App\Http\Requests\API;

use App\Http\Requests\AppBaseFormRequest;
use App\Models\Beneficiary;

class RespondCommunicationAPIRequest extends AppBaseFormRequest {

    public function authorize() {
        return true;
    }

    public function rules() {
        $returned_arr = [
                            'communication_primary_id' => 'required|uuid|max:50',
                            'communication_parent_id' => 'required|uuid|max:50',
                            'communication_table_type' => 'required|string|max:50|in:tf_bip_ben_req_communications,tf_bm_beneficiary_communications',
                            'communication_comment' => 'required|string|max:1000',
                        ];


        if (request()->hasFile('communication_attachments') && count(request()->communication_attachments)>0) {
            $returned_arr['communication_attachments.*'] = 'required|file|mimes:pdf|max:100000';
        } else {
            $returned_arr['communication_attachments'] = 'required';
        }

        return $returned_arr;
    }


    public function attributes() {
        $arr_returned = [
                'communication_primary_id'=>'Communication Primary ID',
                'communication_parent_id'=>'Communication Parent ID',
                'communication_table_type'=>'Communication Table Type',
                'communication_comment'=>'Communication Comment',
                'communication_attachments'=>'Communication Attachments',
        ];

        if (request()->file('communication_attachments') && count(request()->communication_attachments) > 0) {
            $total_attachments = count(request()->communication_attachments);

            for ($i=0; $i<$total_attachments; $i++) {
                $column_name = 'communication_attachments.' . $i;
                $arr_returned[$column_name] = $this->ordinal($i+1) . ' File Attached';
            }

            $arr_returned['communication_attachments.*'] = 'Communication Attachments';
        }

        return $arr_returned;
    }


    function ordinal($number) {
        $suffixes = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
        if (($number % 100) >= 11 && ($number % 100) <= 13) {
            return $number . 'th';
        } else {
            return $number . $suffixes[$number % 10];
        }
    }
}
