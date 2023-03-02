<?php

namespace App\Http\Requests\API;

use App\Models\Beneficiary;
use App\Http\Requests\AppBaseFormRequest;


class UpdateBeneficiaryAPIRequest extends AppBaseFormRequest
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
            'id' => 'required|string|max:36|exists:tf_bi_portal_beneficiaries,id',
            'organization_id' => 'required|string|max:36|exists:fc_organizations,id',
            'email' => 'required|email|unique:tf_bi_portal_beneficiaries,email,'.$this->id,
            'short_name' => 'required|string|max:20',
            //'official_email' => 'required|email|unique:tf_bi_portal_beneficiaries,official_email,'.$this->id,
            'official_website' => 'nullable|url',
            //'type' => 'required|string|max:100',
            'official_phone' => 'required|digits:11',
            'address_street' => 'required|max:200',
            'address_town' => 'required|max:200',
            'address_state' => 'required|max:200',
            'head_of_institution_title' => 'required|max:200',
            'geo_zone' => 'required|max:200',
            'owner_agency_type' => 'required|max:200'
        ];
    }

    public function attributes() {
        return [
            'id' => 'Beneficiary Institution',
            'organization_id' => 'Organization ID',
            'email' => 'Email',
            'short_name' => 'Short Name',
            'official_email' => 'Official Email',
            'official_website' => 'Official Website',
            'type' => 'Institution Type',
            'official_phone' => 'Official Phone',
            'address_street' => 'Address Street',
            'address_town' => 'Address Town',
            'address_state' => 'Address State',
            'head_of_institution_title' => 'Head of Institution Title',
            'geo_zone' => 'Geo Zone',
            'owner_agency_type' => 'Owner Agency Type'
        ];
    }

}
