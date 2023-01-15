<?php

namespace App\Http\Requests\API;

use App\Models\CommitteeMeetingsMinutes;
use App\Http\Requests\AppBaseFormRequest;


class CommitteeMeetingsMinutesUpdateAPIRequest extends AppBaseFormRequest
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
            'nomination_type' => "required|string|max:5|in:tp,ca,tsas",
            'checklist_item_name' => 'required|string|max:255',
            'minute_uploaded_primary_id' => 'required|string|exists:fc_attachments,id|max:150',
            'submissioin_request_primary_id' => 'required|string|exists:tf_bi_submission_requests,id|max:150',
        ];
    }

    public function messages() {
        return [
            'checklist_item_name.required' => 'The :attribute selection field is required',
        ];
    }

    public function attributes() {
        return [
            'nomination_type' => 'Nomination Type',
            'checklist_item_name' => 'Checklist Item Name',
            'minute_uploaded_primary_id' => 'Minutes of Meeting',
            'submissioin_request_primary_id' => 'Submission Request',
        ];
    }

}
