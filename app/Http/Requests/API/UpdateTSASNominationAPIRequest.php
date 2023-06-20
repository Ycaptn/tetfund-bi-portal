<?php
namespace App\Http\Requests\API;

use App\Models\TSASNomination;
use App\Http\Requests\AppBaseFormRequest;
use Hasob\FoundationCore\Controllers\BaseController;


class UpdateTSASNominationAPIRequest extends AppBaseFormRequest
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

    public function prepareForValidation() {
        if ($this->tf_iterum_portal_country_id != $this->country_nigeria_id) {
            $this->request->remove('intitution_state');
        } elseif ($this->tf_iterum_portal_country_id == $this->country_nigeria_id) {
            $this->request->remove('intl_passport_number');   
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $return_arr =  [
            'organization_id' => 'required',
            'display_ordinal' => 'nullable|min:0|max:365',
            'email' => 'required|email|max:190',
            'telephone' => 'required|digits:11',
            'beneficiary_institution_id' => 'required|exists:tf_bi_portal_beneficiaries,id',
            'tf_iterum_portal_country_id' => 'required|uuid',
            // 'tf_iterum_portal_institution_id' => 'required|uuid',
            'institution_name' => 'required|string|max:200',
            'intitution_state' => 'required_if:tf_iterum_portal_country_id,'. $this->country_nigeria_id .'|string|min:2|max:100|in:'. implode(',', BaseController::statesList()),
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
            'bank_verification_number' => 'required|digits:12',
            'intl_passport_number' => 'required_unless:tf_iterum_portal_country_id,'.request()->country_nigeria_id.'|max:100',
            'national_id_number' => 'required|numeric',
            'degree_type' => 'required|max:100',
            'program_title' => 'required|string|max:100',
            'is_science_program' => "required|string|max:50|in:0,1",
            'program_start_date' => 'required|date',
            'program_end_date' => 'required|date|after:program_start_date',
        ];

        if(request()->hasFile('passport_photo') && request()->passport_photo != 'undefined') {
            $return_arr['passport_photo'] = 'file|mimes:pdf,png,jpeg,jpg|max:5240';
        }

        if(request()->hasFile('admission_letter') && request()->admission_letter != 'undefined') {
            $return_arr['admission_letter'] = 'file|mimes:pdf|max:5240';
        }

        if(request()->hasFile('health_report') && request()->health_report != 'undefined') {
            $return_arr['health_report'] = 'file|mimes:pdf,doc,docx|max:5240';
        }

        if(request()->hasFile('curriculum_vitae') && request()->curriculum_vitae != 'undefined') {
            $return_arr['curriculum_vitae'] = 'file|mimes:pdf,doc,docx|max:5240';
        }

        if(request()->hasFile('signed_bond_with_beneficiary') && request()->signed_bond_with_beneficiary != 'undefined') {
            $return_arr['signed_bond_with_beneficiary'] = 'file|mimes:pdf,doc,docx|max:5240';
        }

        if(request()->hasFile('international_passport_bio_page') && request()->international_passport_bio_page != 'undefined') {
            $return_arr['international_passport_bio_page'] = 'file|mimes:pdf,doc,docx|max:5240';
        }

        return $return_arr;
    }

    public function messages() {
        return [
            'intitution_state.required_if' => 'The :attribute field is required when selected Country is Nigeria.',
            'intl_passport_number.required_unless' => 'The :attribute field is required when the selected country isn\'t Nigeria.',
        ];
    }

    public function attributes() {
        return [
            'email' => 'Email',
            'telephone' => 'Telephone',
            'beneficiary_institution_id' => 'Beneficiary Institution',
            'tf_iterum_portal_institution_id' => 'Institution',
            'institution_name' => 'Institution Name',
            'intitution_state' => 'Institution State',
            'tf_iterum_portal_country_id' => 'Country',
            'gender' => 'Gender',
            //'name_title' => 'Name Title',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'last_name' => 'Last Name',
            //'name_suffix' => 'Name Suffix',
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
            
            'passport_photo' => 'Passport Photo',
            'admission_letter' => 'Admission Letter',
            'health_report' => 'Health Report',
            'curriculum_vitae' => 'Curriculum Vitae',
            'international_passport_bio_page' => 'International Passport Bio Page',
            'signed_bond_with_beneficiary' => 'Signed Bond With Beneficiary',
        ];
    }

}
