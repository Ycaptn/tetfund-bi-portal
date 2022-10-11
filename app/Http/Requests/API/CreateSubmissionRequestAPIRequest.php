<?php

namespace App\Http\Requests\API;

use App\Models\SubmissionRequest;
use App\Http\Requests\AppBaseFormRequest;


class CreateSubmissionRequestAPIRequest extends AppBaseFormRequest
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
    *     title="title",
    *     description="title",
    *     type="string"
    * )
    */
    public $title;

    /**
    * @OA\Property(
    *     title="status",
    *     description="status",
    *     type="string"
    * )
    */
    public $status;

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
    *     title="requesting_user_id",
    *     description="requesting_user_id",
    *     type="string"
    * )
    */
    public $requesting_user_id;

    /**
    * @OA\Property(
    *     title="beneficiary_id",
    *     description="beneficiary_id",
    *     type="string"
    * )
    */
    public $beneficiary_id;

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
    *     title="intervention_year1",
    *     description="intervention_year1",
    *     type="integer"
    * )
    */
    public $intervention_year1;

    /**
    * @OA\Property(
    *     title="intervention_year2",
    *     description="intervention_year2",
    *     type="integer"
    * )
    */
    public $intervention_year2;

    /**
    * @OA\Property(
    *     title="intervention_year3",
    *     description="intervention_year3",
    *     type="integer"
    * )
    */
    public $intervention_year3;

    /**
    * @OA\Property(
    *     title="intervention_year4",
    *     description="intervention_year4",
    *     type="integer"
    * )
    */
    public $intervention_year4;

    /**
    * @OA\Property(
    *     title="proposed_request_date",
    *     description="proposed_request_date",
    *     type="string"
    * )
    */
    public $proposed_request_date;

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
    *     title="tf_iterum_portal_request_status",
    *     description="tf_iterum_portal_request_status",
    *     type="string"
    * )
    */
    public $tf_iterum_portal_request_status;

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
