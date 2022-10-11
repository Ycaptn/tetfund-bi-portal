<?php

namespace App\Http\Requests;

use App\Http\Requests\AppBaseFormRequest;
use App\Models\Beneficiary;

class UpdateBeneficiaryRequest extends AppBaseFormRequest
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
        'display_ordinal' => 'nullable|min:0|max:365',
        'email' => 'required|email',
        'full_name' => 'required',
        'short_name' => 'required',
        'official_email' => 'max:200',
        'official_website' => 'max:200',
        'type' => 'max:100',
        'official_phone' => 'size:11',
        'address_street' => 'nullable|max:200',
        'address_town' => 'nullable|max:200',
        'address_state' => 'nullable|max:200',
        'head_of_institution_title' => 'nullable|max:200',
        'geo_zone' => 'nullable|max:200',
        'owner_agency_type' => 'nullable|max:200',
        'tf_iterum_portal_beneficiary_status' => 'required',
        'tf_iterum_portal_response_meta_data' => 'max:1000'
        ];
    }
}
