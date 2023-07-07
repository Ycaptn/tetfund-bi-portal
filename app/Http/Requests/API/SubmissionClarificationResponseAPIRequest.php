<?php

namespace App\Http\Requests\API;

use App\Models\SubmissionRequest;
use App\Http\Requests\AppBaseFormRequest;


class SubmissionClarificationResponseAPIRequest extends AppBaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $returned_arr = [
                        'submission_request_id' => 'required|exists:tf_bi_submission_requests,id',
                        'text_clarificarion_response' => 'required|string|max:1000'
                    ];

        if (request()->hasFile('attachments_clarification_response') && count(request()->attachments_clarification_response)>0) {
            $returned_arr['attachments_clarification_response.*'] = 'sometimes|file|mimes:pdf,doc,docx,jpg,png,jpeg,xls,xlsx|max:52400';
        } else {
            $returned_arr['attachments_clarification_response'] = 'sometimes|file|mimes:pdf,doc,docx,jpg,png,jpeg,xls,xlsx|max:52400';
        }

        return $returned_arr;
    }

    public function attributes() {
        $arr_returned = [
                    'submission_request_id' =>' Submission Request',
                    'text_clarificarion_response' =>' Clarificarion/Query Response',
                    'attachments_clarification_response' =>' Optional Attachments',
                ];


        if (request()->file('attachments_clarification_response') && count(request()->attachments_clarification_response) > 0) {
            $total_attachments = count(request()->attachments_clarification_response);

            for ($i=0; $i<$total_attachments; $i++) {
                $column_name = 'attachments_clarification_response.' . $i;
                $arr_returned[$column_name] = $this->ordinal($i+1) . ' File Attached';
            }

            $arr_returned['attachments_clarification_response.*'] = 'Optional Attachments';
        }

        return $arr_returned;
    }

    public function messages() {
        return [
            'submission_request_id.required' => 'This :attribute is invalid.',
            'submission_request_id.exists' => 'This :attribute is invalid.',
        ];
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
