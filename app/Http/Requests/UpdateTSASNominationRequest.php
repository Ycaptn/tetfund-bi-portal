<?php

namespace App\Http\Requests;

use App\Http\Requests\AppBaseFormRequest;
use App\Models\TSASNomination;

class UpdateTSASNominationRequest extends AppBaseFormRequest
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
            //'institution_id' => 'required|exists:tf_astd_institutions,id',
            //'country_id' => 'required|exists:tf_astd_countries,id',
            'tf_iterum_portal_institution_id' => 'required|uuid',
            'tf_iterum_portal_country_id' => 'required|uuid',
            'nomination_request_id' => 'required|exists:tf_bi_nomination_requests,id',
            'user_id' => 'required|exists:fc_users,id',
            'gender' => "required|string|max:50|in:male,female",
            'name_title' => 'nullable|string|max:50',
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'name_suffix' => 'nullable|string|max:100',
            'bank_account_name' => 'required|min:2|max:190',
            'bank_account_number' => 'required|digits:10',
            'bank_name' => 'required|max:100',
            'bank_sort_code' => 'required|max:100',
            'intl_passport_number' => 'required_unless:tf_iterum_portal_country_id,'.request()->country_nigeria_id.'|max:100',
            'bank_verification_number' => 'required|numeric',
            'national_id_number' => 'required|numeric',
            'degree_type' => 'required|max:100',
            'program_title' => 'required|string|max:100',
            'is_science_program' => "required|string|max:50|in:0,1",
            'program_start_date' => 'nullable|date|after:today',
            'program_end_date' => 'nullable|date|after:program_start_date',
            // 'program_duration_months' => 'nullable|min:0|max:365',
            // 'fee_amount' => 'nullable|numeric|min:0|max:100000000',
            // 'tuition_amount' => 'nullable|numeric|min:0|max:100000000',
            // 'upgrade_fee_amount' => 'nullable|numeric|min:0|max:100000000',
            // 'stipend_amount' => 'nullable|numeric|min:0|max:100000000',
            // 'passage_amount' => 'nullable|numeric|min:0|max:100000000',
            // 'medical_amount' => 'nullable|numeric|min:0|max:100000000',
            // 'warm_clothing_amount' => 'nullable|numeric|min:0|max:100000000',
            // 'study_tours_amount' => 'nullable|numeric|min:0|max:100000000',
            // 'education_materials_amount' => 'nullable|numeric|min:0|max:100000000',
            // 'thesis_research_amount' => 'nullable|numeric|min:0|max:100000000',
            // 'final_remarks' => 'nullable|string|max:500',
            // 'total_requested_amount' => 'nullable|numeric|min:0|max:100000000',
            // 'total_approved_amount' => 'nullable|numeric|min:0|max:100000000'
        ];
    }

    public function messages() {
        return [
            'intl_passport_number.required_unless' => 'The :attribute field is required when the selected country isn\'t Nigeria.',
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
}
