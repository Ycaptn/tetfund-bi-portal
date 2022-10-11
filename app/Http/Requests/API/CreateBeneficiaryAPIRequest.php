<?php

namespace App\Http\Requests\API;

use App\Models\Beneficiary;
use App\Http\Requests\AppBaseFormRequest;


class CreateBeneficiaryAPIRequest extends AppBaseFormRequest
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

    /**
    * @OA\Property(
    *     title="organization_id",
    *     description="organization_id",
    *     type="string"
    * )
    */
    public $organization_id;

    /**
    * @OA\Property(
    *     title="display_ordinal",
    *     description="display_ordinal",
    *     type="integer"
    * )
    */
    public $display_ordinal;

    /**
    * @OA\Property(
    *     title="email",
    *     description="email",
    *     type="string"
    * )
    */
    public $email;

    /**
    * @OA\Property(
    *     title="full_name",
    *     description="full_name",
    *     type="string"
    * )
    */
    public $full_name;

    /**
    * @OA\Property(
    *     title="short_name",
    *     description="short_name",
    *     type="string"
    * )
    */
    public $short_name;

    /**
    * @OA\Property(
    *     title="official_email",
    *     description="official_email",
    *     type="string"
    * )
    */
    public $official_email;

    /**
    * @OA\Property(
    *     title="official_website",
    *     description="official_website",
    *     type="string"
    * )
    */
    public $official_website;

    /**
    * @OA\Property(
    *     title="type",
    *     description="type",
    *     type="string"
    * )
    */
    public $type;

    /**
    * @OA\Property(
    *     title="official_phone",
    *     description="official_phone",
    *     type="string"
    * )
    */
    public $official_phone;

    /**
    * @OA\Property(
    *     title="address_street",
    *     description="address_street",
    *     type="string"
    * )
    */
    public $address_street;

    /**
    * @OA\Property(
    *     title="address_town",
    *     description="address_town",
    *     type="string"
    * )
    */
    public $address_town;

    /**
    * @OA\Property(
    *     title="address_state",
    *     description="address_state",
    *     type="string"
    * )
    */
    public $address_state;

    /**
    * @OA\Property(
    *     title="head_of_institution_title",
    *     description="head_of_institution_title",
    *     type="string"
    * )
    */
    public $head_of_institution_title;

    /**
    * @OA\Property(
    *     title="geo_zone",
    *     description="geo_zone",
    *     type="string"
    * )
    */
    public $geo_zone;

    /**
    * @OA\Property(
    *     title="owner_agency_type",
    *     description="owner_agency_type",
    *     type="string"
    * )
    */
    public $owner_agency_type;

    /**
    * @OA\Property(
    *     title="tf_iterum_portal_key_id",
    *     description="tf_iterum_portal_key_id",
    *     type="string"
    * )
    */
    public $tf_iterum_portal_key_id;

    /**
    * @OA\Property(
    *     title="tf_iterum_portal_beneficiary_status",
    *     description="tf_iterum_portal_beneficiary_status",
    *     type="string"
    * )
    */
    public $tf_iterum_portal_beneficiary_status;

    /**
    * @OA\Property(
    *     title="tf_iterum_portal_response_meta_data",
    *     description="tf_iterum_portal_response_meta_data",
    *     type="string"
    * )
    */
    public $tf_iterum_portal_response_meta_data;

    /**
    * @OA\Property(
    *     title="tf_iterum_portal_response_at",
    *     description="tf_iterum_portal_response_at",
    *     type="string"
    * )
    */
    public $tf_iterum_portal_response_at;


}
