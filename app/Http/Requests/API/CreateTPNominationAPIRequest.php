<?php

namespace App\Http\Requests\API;

use App\Models\TPNomination;
use App\Http\Requests\AppBaseFormRequest;
use Hasob\FoundationCore\Controllers\BaseController;


class CreateTPNominationAPIRequest extends AppBaseFormRequest
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
        $start_date = $this->program_start_date;
        $plus_3_days = date('d-M-Y', strtotime($start_date . ' + 3 days'));

        $return_rules = [
            'organization_id' => 'required',
            'display_ordinal' => 'nullable|min:0|max:365',
            'email' => 'required|email|max:190',
            'telephone' => 'required|digits:11',
            'beneficiary_institution_id' => 'required|exists:tf_bi_portal_beneficiaries,id',
            // 'tf_iterum_portal_institution_id' => 'required|uuid',
            'institution_name' => 'required|string|min:2|max:100',
            'intitution_state' => "required|string|min:2|max:100|in:". implode(',', BaseController::statesList()),
            'institution_address' => 'required|string|max:200',
            'gender' => "required|string|max:50|in:male,female",
            'rank_gl_equivalent' => 'required|string|max:50',
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'name_suffix' => 'nullable|string|max:100',
            'bank_account_name' => 'required|min:2|max:190',
            'bank_account_number' => 'required|digits:10',
            'bank_name' => 'required|max:100',
            'bank_sort_code' => 'required|max:100',
           // 'bank_verification_number' => 'required|digits:12',
            'national_id_number' => 'nullable|numeric',
            'degree_type' => 'nullable|max:100',
            'program_title' => 'nullable|string|max:100',
            'program_type' => 'nullable|max:100',
            'is_science_program' => "nullable|string|max:50|in:0,1",
            'program_start_date' => 'required|date|after:today',
            'program_end_date' => 'required|date|after_or_equal:program_start_date|before:'.$plus_3_days,

            'passport_photo' => 'required|file|mimes:pdf,png,jpeg,jpg|max:5240',
            'invitation_letter' => 'required|file|mimes:pdf|max:5240',
        ];

        if (!(request()->has('nomination_request_and_submission'))) {
            $return_rules['nomination_request_id'] = 'required|exists:tf_bi_nomination_requests,id';
            $return_rules['user_id'] = 'required|exists:fc_users,id';
        }

        return $return_rules;
    }

    public function attributes() {
        return [
            'email' => 'Email',
            'telephone' => 'Telephone',
            'beneficiary_institution_id' => 'Beneficiary Institution',
            'tf_iterum_portal_institution_id' => 'Institution',
            'institution_name' => 'Institution FullName',
            'intitution_state' => 'Institution State',
            'institution_address' => 'Institution Address',
            'tf_iterum_portal_country_id' => 'Country',
            'gender' => 'Gender',
            'rank_gl_equivalent' => 'Rank or GL Equivalent',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'last_name' => 'Last Name',
            //'name_suffix' => 'Name Suffix',
            'bank_account_name' => 'Bank Account Name',
            'bank_account_number' => 'Bank Account Number',
            'bank_name' => 'Bank Name',
            'bank_sort_code' => 'Bank Sort Code',
            'intl_passport_number' => 'Intl Passport Number',
          //  'bank_verification_number' => 'Bank Verification Number',
            'national_id_number' => 'National Id Number',
            'degree_type' => ' Degree Type',
            'program_title' => ' Program Title',
            'program_type' => ' Program Type',
            'is_science_program' => 'Is Science Program',
            'program_start_date' => 'Program Start Date',
            'program_end_date' => 'Program End Date',
            
            'passport_photo' => 'Passport Photo',
            'invitation_letter' => 'Invitation Letter',
        ];
    }

    public function messages(): array
    {
        return [
            'gender.in' => 'Invalid gender. Please update your profile',
            'telephone.digits' => 'Invalid telephone number. Please update your profile',
        ];
    }

}
