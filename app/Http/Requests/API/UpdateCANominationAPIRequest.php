<?php
namespace App\Http\Requests\API;

use App\Models\CANomination;
use App\Http\Requests\AppBaseFormRequest;
use Hasob\FoundationCore\Controllers\BaseController;


class UpdateCANominationAPIRequest extends AppBaseFormRequest
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
        $start_date = $this->conference_start_date;
        $plus_5_days = date('d-M-Y', strtotime($start_date . ' + 5 days'));

        $return_arr =  [
            'organization_id' => 'required',
            'display_ordinal' => 'nullable|min:0|max:365',
            'beneficiary_institution_id' => 'required|exists:tf_bi_portal_beneficiaries,id',
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:190',
            'gender' => "required|string|max:50|in:". implode(['male', 'female'], ','),
            'telephone' => 'required|digits:11',
            'is_conference_workshop' => "required_if:is_academic_staff,=,0|string|max:50|in:". implode(['0', '1'], ','),
            'tf_iterum_portal_country_id' => 'required|uuid',
            //'tf_iterum_portal_conference_id' => 'required|uuid',
            'nomination_request_id' => 'required|exists:tf_bi_nomination_requests,id',
            'conference_state' => "required_if:tf_iterum_portal_country_id,=,". request()->country_nigeria_id ."|string|min:2|max:100|in:". implode(BaseController::statesList(), ','),
            'conference_title' => 'required|string|min:2|max:100',
            'organizer_name' => 'required|string|max:190',
            'conference_theme' => 'required|string|max:190',
            'conference_address' => 'required|string|max:200',
            'conference_passage_type' => "required|string|max:50|in:". implode(['short', 'medium', 'long', 'state', 'africa'], ','),
            'attendee_department_name' => 'required|string|max:190',
            'attendee_grade_level' => 'required|string|max:190',
            'has_paper_presentation' => "required|string|max:50|in:". implode(['0', '1'], ','),
            'accepted_paper_title' => 'required_if:has_paper_presentation,=,1|nullable|string|max:190',
            'is_academic_staff' => "required|string|max:50|in:". implode(['0', '1'], ','),
            'conference_start_date' => 'required|date|after:today',
            'conference_end_date' => 'required|date|after_or_equal:conference_start_date|before:'.$plus_5_days,
            'name_title' => 'nullable|string|max:50',
            'name_suffix' => 'nullable|string|max:100',
            'bank_account_name' => 'required|min:2|max:190',
            'bank_account_number' => 'required|digits:10',
            'bank_name' => 'required|max:100',
            'bank_sort_code' => 'required|max:100',
            'bank_verification_number' => 'required|numeric',
            'intl_passport_number' => 'required_unless:tf_iterum_portal_country_id,'.request()->country_nigeria_id.'|max:100',
            'national_id_number' => 'required|numeric',

            'conference_fee_amount_local' => 'required|numeric|min:0|max:100000000',
        ];

        if(request()->hasFile('passport_photo') && request()->passport_photo != 'undefined') {
            $return_arr['passport_photo'] = 'file|mimes:pdf,png,jpeg,jpg|max:5240';
        }

        if(request()->hasFile('conference_attendance_letter') && request()->conference_attendance_letter != 'undefined') {
            $return_arr['conference_attendance_letter'] = 'file|mimes:pdf|max:5240';
        }

        if(request()->hasFile('paper_presentation') && request()->paper_presentation != 'undefined') {
            $return_arr['paper_presentation'] = 'file|mimes:pdf,doc,docx|max:5240';
        }

        if(request()->hasFile('international_passport_bio_page') && request()->international_passport_bio_page != 'undefined') {
            $return_arr['international_passport_bio_page'] = 'file|mimes:pdf,doc,docx|max:5240';
        }

        return $return_arr;
    }

    public function messages() {
        return [
            'is_conference_workshop.required_if' => 'The :attribute field is required when Staff is Non-Academic.',
            'conference_state.required_if' => 'The :attribute field is required when selected Country is Nigeria.',
            'paper_presentation.required_if' => 'The Presentation Paper attachment is required.',
            'accepted_paper_title.required_if' => 'The :attribute field is required.',
            'intl_passport_number.required_unless' => 'The :attribute field is required when the selected country isn\'t Nigeria.'
        ];
    }
    
    public function attributes() {
        return [
            'email' => 'Email',
            'telephone' => 'Telephone',
            'beneficiary_institution_id' => 'Beneficiary Institution',
            'is_conference_workshop' => 'Is Conference More Like A Workshop',
            'tf_iterum_portal_country_id' => 'Country',
            'conference_title' => 'Conference Title',
            'state_id' => 'State',
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
 
            'organizer_name' => 'Organizer Name',
            'conference_theme' => 'Conference Theme',
            'conference_address' => 'Conference Address',
            'conference_passage_type' => 'Conference Passage Type',
            'accepted_paper_title' => 'Accepted Presentation Paper Title',
            'attendee_department_name' => 'Attendee Department Name',
            'attendee_grade_level' => 'Attendee Rank or GL Equivalent',
            'has_paper_presentation' => 'Any Paper Presentation',
            'is_academic_staff' => 'Staff Type',
            'conference_start_date' => 'Conference Start Date',
            'conference_end_date' => 'Conference End Date',
            'conference_fee_amount' => 'Conference Fee Amount',
            'conference_fee_amount_local' => 'Conference Fee Amount (₦)',
            
            'passport_photo' => 'Passport Photo',
            'conference_attendance_letter' => 'Conference Attendance Letter',
            'paper_presentation' => 'Presentation Paper',
            'international_passport_bio_page' => 'International Passport Bio Page',
        ];
    }

}
