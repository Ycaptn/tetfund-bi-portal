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
    public function rules()
    {
        /*
        
        */
        return [
            'organization_id' => 'required',
        'title' => 'required|max:300',
        'status' => 'nullable|max:100',
        'type' => 'max:100',
        'requesting_user_id' => 'required',
        'beneficiary_id' => 'required',
        'display_ordinal' => 'nullable|min:0|max:365',
        'tf_iterum_portal_request_status' => 'required',
        'tf_iterum_portal_response_meta_data' => 'max:1000'
        ];
    }


}
