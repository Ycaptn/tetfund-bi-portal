<?php

namespace App\Http\Requests\API;

use App\Models\ASTDNomination as TPNomination;
use App\Http\Requests\AppBaseFormRequest;

class UpdateTPNominationAPIRequest extends AppBaseFormRequest
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
            'organization_id' => 'required',
            'display_ordinal' => 'nullable|min:0|max:365',
            'email' => 'required|email|max:190',
            'telephone' => 'required|digits:11',
            'beneficiary_institution_id' => 'required|exists:tf_bi_portal_beneficiaries,id',
            'bi_submission_request_id' => 'required|exists:tf_bi_submission_requests,id',
            //'institution_id' => 'required|exists:tf_astd_institutions,id',
            //'country_id' => 'required|exists:tf_astd_countries,id',
            'tf_iterum_portal_institution_id' => 'required|uuid',
            'tf_iterum_portal_country_id' => 'required|uuid',
            'nomination_request_id' => 'required|exists:tf_bi_nomination_requests,id',
            'user_id' => 'required|exists:fc_users,id',
            'gender' => "required|string|max:50|in:". implode(['Male', 'Female'], ','),
            'name_title' => 'nullable|string|max:50',
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'name_suffix' => 'nullable|string|max:100',
            'bank_account_name' => 'required|min:2|max:190',
            'bank_account_number' => 'required|digits:10',
            'bank_name' => 'required|max:100',
            'bank_sort_code' => 'required|max:100',
            'intl_passport_number' => 'required|max:100',
            'bank_verification_number' => 'required|numeric',
            'national_id_number' => 'required|numeric',
            'degree_type' => 'required|max:100',
            'program_title' => 'required|string|max:100',
            'program_type' => 'required|max:100',
            'is_science_program' => "required|string|max:50|in:". implode(['0', '1'], ','),
            'program_start_date' => 'nullable|date|after:today',
            'program_end_date' => 'nullable|date|after:program_start_date',
            /*'program_duration_months' => 'nullable|min:0|max:365',*/
            'fee_amount' => 'nullable|numeric|min:0|max:100000000',
            'tuition_amount' => 'nullable|numeric|min:0|max:100000000',
            'upgrade_fee_amount' => 'nullable|numeric|min:0|max:100000000',
            'stipend_amount' => 'nullable|numeric|min:0|max:100000000',
            'passage_amount' => 'nullable|numeric|min:0|max:100000000',
            'medical_amount' => 'nullable|numeric|min:0|max:100000000',
            'warm_clothing_amount' => 'nullable|numeric|min:0|max:100000000',
            'study_tours_amount' => 'nullable|numeric|min:0|max:100000000',
            'education_materials_amount' => 'nullable|numeric|min:0|max:100000000',
            'thesis_research_amount' => 'nullable|numeric|min:0|max:100000000',
            'final_remarks' => 'nullable|string|max:500',
            'total_requested_amount' => 'nullable|numeric|min:0|max:100000000',
            'total_approved_amount' => 'nullable|numeric|min:0|max:100000000'
        ];
    }

    public function attributes() {
        return [
            'email' => 'Email',
            'telephone' => 'Telephone',
            'beneficiary_institution_id' => 'Beneficiary Institution',
            'tf_iterum_portal_institution_id' => 'Institution',
            'tf_iterum_portal_country_id' => 'Country',
            'gender' => 'Gender',
            'name_title' => 'Name Title',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'last_name' => 'Last Name',
            'name_suffix' => 'Name Suffix',
            'bank_account_name' => 'Bank Account Name',
            'bank_account_number' => 'Bank Account Number',
            'bank_name' => 'Bank Name',
            'bank_sort_code' => 'Bank Sort Code',
            'intl_passport_number' => 'Intl Passport Number',
            'bank_verification_number' => 'Bank Verification Number',
            'national_id_number' => 'National Id Number',
            'degree_type' => ' Degree Type',
            'program_title' => ' Program Title',
            'program_type' => ' Program Type',
            'is_science_program' => 'Is Science Program',
            'program_start_date' => 'Program Start Date',
            'program_end_date' => 'Program End Date',
            'fee_amount' => 'Fee Amount',
            'tuition_amount' => 'Tuition Amount',
            'upgrade_fee_amount' => 'Upgrade Fee Amount',
            'stipend_amount' => 'Stipend Amount',
            'passage_amount' => 'Passage Amount',
            'medical_amount' => 'Medical Amount',
            'warm_clothing_amount' => 'Warm Clothing Amount',
            'study_tours_amount' => 'Study Tours Amount',
            'education_materials_amount' => 'Education Materials Amount',
            'thesis_research_amount' => 'Thesis Research Amount',
            'final_remarks' => 'Final Remarks',
            'total_requested_amount' => 'Total Requested Amount:',
            'total_approved_amount' => 'Total Approved Amount'
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
    *     title="telephone",
    *     description="telephone",
    *     type="string"
    * )
    */
    public $telephone;

    /**
    * @OA\Property(
    *     title="beneficiary_institution_id",
    *     description="beneficiary_institution_id",
    *     type="string"
    * )
    */
    public $beneficiary_institution_id;

    /**
    * @OA\Property(
    *     title="institution_id",
    *     description="institution_id",
    *     type="string"
    * )
    */
    public $institution_id;

    /**
    * @OA\Property(
    *     title="country_id",
    *     description="country_id",
    *     type="string"
    * )
    */
    public $country_id;

    /**
    * @OA\Property(
    *     title="user_id",
    *     description="user_id",
    *     type="string"
    * )
    */
    public $user_id;

    /**
    * @OA\Property(
    *     title="gender",
    *     description="gender",
    *     type="string"
    * )
    */
    public $gender;

    /**
    * @OA\Property(
    *     title="name_title",
    *     description="name_title",
    *     type="string"
    * )
    */
    public $name_title;

    /**
    * @OA\Property(
    *     title="first_name",
    *     description="first_name",
    *     type="string"
    * )
    */
    public $first_name;

    /**
    * @OA\Property(
    *     title="middle_name",
    *     description="middle_name",
    *     type="string"
    * )
    */
    public $middle_name;

    /**
    * @OA\Property(
    *     title="last_name",
    *     description="last_name",
    *     type="string"
    * )
    */
    public $last_name;

    /**
    * @OA\Property(
    *     title="name_suffix",
    *     description="name_suffix",
    *     type="string"
    * )
    */
    public $name_suffix;

    /**
    * @OA\Property(
    *     title="face_picture_attachment_id",
    *     description="face_picture_attachment_id",
    *     type="string"
    * )
    */
    public $face_picture_attachment_id;

    /**
    * @OA\Property(
    *     title="bank_account_name",
    *     description="bank_account_name",
    *     type="string"
    * )
    */
    public $bank_account_name;

    /**
    * @OA\Property(
    *     title="bank_account_number",
    *     description="bank_account_number",
    *     type="string"
    * )
    */
    public $bank_account_number;

    /**
    * @OA\Property(
    *     title="bank_name",
    *     description="bank_name",
    *     type="string"
    * )
    */
    public $bank_name;

    /**
    * @OA\Property(
    *     title="bank_sort_code",
    *     description="bank_sort_code",
    *     type="string"
    * )
    */
    public $bank_sort_code;

    /**
    * @OA\Property(
    *     title="intl_passport_number",
    *     description="intl_passport_number",
    *     type="string"
    * )
    */
    public $intl_passport_number;

    /**
    * @OA\Property(
    *     title="bank_verification_number",
    *     description="bank_verification_number",
    *     type="string"
    * )
    */
    public $bank_verification_number;

    /**
    * @OA\Property(
    *     title="national_id_number",
    *     description="national_id_number",
    *     type="string"
    * )
    */
    public $national_id_number;

    /**
    * @OA\Property(
    *     title="degree_type",
    *     description="degree_type",
    *     type="string"
    * )
    */
    public $degree_type;

    /**
    * @OA\Property(
    *     title="program_title",
    *     description="program_title",
    *     type="string"
    * )
    */
    public $program_title;

    /**
    * @OA\Property(
    *     title="program_type",
    *     description="program_type",
    *     type="string"
    * )
    */
    public $program_type;

    /**
    * @OA\Property(
    *     title="is_science_program",
    *     description="is_science_program",
    *     type="boolean"
    * )
    */
    public $is_science_program;

    /**
    * @OA\Property(
    *     title="program_start_date",
    *     description="program_start_date",
    *     type="string"
    * )
    */
    public $program_start_date;

    /**
    * @OA\Property(
    *     title="program_end_date",
    *     description="program_end_date",
    *     type="string"
    * )
    */
    public $program_end_date;

    /**
    * @OA\Property(
    *     title="program_duration_months",
    *     description="program_duration_months",
    *     type="integer"
    * )
    */
    public $program_duration_months;

    /**
    * @OA\Property(
    *     title="fee_amount",
    *     description="fee_amount",
    *     type="number"
    * )
    */
    public $fee_amount;

    /**
    * @OA\Property(
    *     title="tuition_amount",
    *     description="tuition_amount",
    *     type="number"
    * )
    */
    public $tuition_amount;

    /**
    * @OA\Property(
    *     title="upgrade_fee_amount",
    *     description="upgrade_fee_amount",
    *     type="number"
    * )
    */
    public $upgrade_fee_amount;

    /**
    * @OA\Property(
    *     title="stipend_amount",
    *     description="stipend_amount",
    *     type="number"
    * )
    */
    public $stipend_amount;

    /**
    * @OA\Property(
    *     title="passage_amount",
    *     description="passage_amount",
    *     type="number"
    * )
    */
    public $passage_amount;

    /**
    * @OA\Property(
    *     title="medical_amount",
    *     description="medical_amount",
    *     type="number"
    * )
    */
    public $medical_amount;

    /**
    * @OA\Property(
    *     title="warm_clothing_amount",
    *     description="warm_clothing_amount",
    *     type="number"
    * )
    */
    public $warm_clothing_amount;

    /**
    * @OA\Property(
    *     title="study_tours_amount",
    *     description="study_tours_amount",
    *     type="number"
    * )
    */
    public $study_tours_amount;

    /**
    * @OA\Property(
    *     title="education_materials_amount",
    *     description="education_materials_amount",
    *     type="number"
    * )
    */
    public $education_materials_amount;

    /**
    * @OA\Property(
    *     title="thesis_research_amount",
    *     description="thesis_research_amount",
    *     type="number"
    * )
    */
    public $thesis_research_amount;

    /**
    * @OA\Property(
    *     title="final_remarks",
    *     description="final_remarks",
    *     type="string"
    * )
    */
    public $final_remarks;

    /**
    * @OA\Property(
    *     title="total_requested_amount",
    *     description="total_requested_amount",
    *     type="number"
    * )
    */
    public $total_requested_amount;

    /**
    * @OA\Property(
    *     title="total_approved_amount",
    *     description="total_approved_amount",
    *     type="number"
    * )
    */
    public $total_approved_amount;


}
