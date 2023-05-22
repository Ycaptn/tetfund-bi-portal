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
        return [
            'submission_request_id' => 'required|exists:tf_bi_submission_requests,id',
            'text_clarificarion_response' => 'required|string|max:1000',
            'attachment_clarificarion_response' => 'sometimes|file|mimes:pdf,doc,docx,jpg,png,jpeg,xls,xlsx|max:52400'
        ];
    }

    public function attributes() {
        return [
            'submission_request_id' =>' Submission Request',
            'text_clarificarion_response' =>' Clarificarion/Query Response',
            'attachment_clarificarion_response' =>' Optinal Attachment',
        ];
    }

    public function messages() {
        return [
            'submission_request_id.required' => 'This :attribute is invalid.',
            'submission_request_id.exists' => 'This :attribute is invalid.',
        ];
    }

}
