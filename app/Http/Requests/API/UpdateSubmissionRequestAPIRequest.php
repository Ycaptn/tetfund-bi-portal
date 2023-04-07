<?php

namespace App\Http\Requests\API;

use App\Models\SubmissionRequest;
use App\Http\Requests\AppBaseFormRequest;


class UpdateSubmissionRequestAPIRequest extends AppBaseFormRequest
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

        // reqiure proposed request date field when submission is monitoring request
        if (request()->has('is_monitoring_request') && request()->is_monitoring_request == true) {
            $return_arr['type'] = 'required|string|max:300';
            $return_arr['proposed_request_date'] = 'required|date|after:today';
            $return_arr['optional_attachment'] = 'sometimes|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,png,jpeg,twg|max:52400';
        }

        return $return_arr ?? [];
    }

    public function attributes() {
        return [
            'type'=>'Type of Monitoring Request',
            'proposed_request_date'=>'Proposed Monitoring Date',
            'optional_attachment'=>'Optional Attachment',
        ];
    }


}
