<?php
namespace App\Http\Requests\API;

use App\Models\CANomination;
use App\Http\Requests\AppBaseFormRequest;


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
        $return_arr =  [
            'organization_id' => 'required',
            'display_ordinal' => 'nullable|min:0|max:365',
            'email' => 'required|email|max:190',
            'telephone' => 'required|digits:11',
            'beneficiary_institution_id' => 'required|exists:tf_bi_portal_beneficiaries,id',
            //'institution_id' => 'required|exists:tf_astd_institutions,id',
            //'country_id' => 'required|exists:tf_astd_countries,id',
            'tf_iterum_portal_country_id' => 'required|uuid',
            'tf_iterum_portal_conference_id' => 'required|uuid',
            'nomination_request_id' => 'required|exists:tf_bi_nomination_requests,id',
            'gender' => "required|string|max:50|in:". implode(['male', 'female'], ','),
            'name_title' => 'nullable|string|max:50',
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'name_suffix' => 'nullable|string|max:100',
            'bank_account_name' => 'required|min:2|max:190',
            'bank_account_number' => 'required|digits:10',
            'bank_name' => 'required|max:100',
            'bank_sort_code' => 'required|max:100',
            'bank_verification_number' => 'required|numeric',
            'intl_passport_number' => 'required_unless:tf_iterum_portal_country_id,'.request()->country_nigeria_id.'|max:100',
            'national_id_number' => 'required|numeric',
            'organizer_name' => 'required|string|max:190',
            'conference_theme' => 'required|string|max:190',
            'attendee_department_name' => 'required|string|max:190',
            'attendee_grade_level' => 'required|string|max:190',
            'has_paper_presentation' => "required|string|max:50|in:". implode(['0', '1'], ','),
            'accepted_paper_title' => 'required_if:has_paper_presentation,=,1|nullable|string|max:190',
            'is_academic_staff' => "required|string|max:50|in:". implode(['0', '1'], ','),
            'conference_start_date' => 'required|date|after:today',
            'conference_end_date' => 'required|date|after:conference_start_date',

            /*'conference_duration_days' => 'nullable|min:0|max:365',
            'conference_fee_amount' => 'nullable|numeric|min:0|max:100000000',
            'conference_fee_amount_local' => 'nullable|numeric|min:0|max:100000000',
            'dta_amount' => 'nullable|numeric|min:0|max:100000000',
            'local_runs_amount' => 'nullable|numeric|min:0|max:100000000',
            'passage_amount' => 'nullable|numeric|min:0|max:100000000',
            'final_remarks' => 'nullable|string|max:500',
            'total_requested_amount' => 'nullable|numeric|min:0|max:100000000',
            'total_approved_amount' => 'nullable|numeric|min:0|max:100000000'*/
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
            'paper_presentation.required_if' => 'The Presentation Paper attachment is required when Any Paper Presentation is YES.',
            'accepted_paper_title.required_if' => 'The :attribute field is required when Any Paper Presentation is YES.',
            'intl_passport_number.required_unless' => 'The :attribute field is required when the selected country isn\'t Nigeria.',
        ];
    }
    
    public function attributes() {
        return [
            'email' => 'Email',
            'telephone' => 'Telephone',
            'beneficiary_institution_id' => 'Beneficiary Institution',
            'tf_iterum_portal_country_id' => 'Country',
            'tf_iterum_portal_conference_id' => 'Conference',
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
            'accepted_paper_title' => 'Accepted Paper Title',
            'attendee_department_name' => 'Attendee Department Name',
            'attendee_grade_level' => 'Attendee Rank or GL Equivalent',
            'has_paper_presentation' => 'Any Paper Presentation',
            'is_academic_staff' => 'Staff Type',
            'conference_start_date' => 'Conference Start Date',
            'conference_end_date' => 'Conference End Date',
            'conference_fee_amount' => 'Conference Fee Amount',
            'conference_fee_amount_local' => 'Conference Fee Amount Local',
            'dta_amount' => 'DTA Amount',
            'local_runs_amount' => 'Local Runs Amount',
            'passage_amount' => 'Passage Amount',
            'final_remarks' => 'Final Remarks',
            'total_requested_amount' => 'Total Requested Amount',
            'total_approved_amount' => 'Total Approved Aamount',
        
            'passport_photo' => 'Passport Photo',
            'conference_attendance_letter' => 'Conference Attendance Letter',
            'paper_presentation' => 'Presentation Paper',
            'international_passport_bio_page' => 'International Passport Bio Page',
        ];
    }

}
