<?php

namespace App\Http\Requests\API;

use App\Models\SubmissionRequest;
use App\Http\Requests\AppBaseFormRequest;


class CreateNominationRequestAPIRequest extends AppBaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $gender_arr = ['male', 'female'];
        $nomination_type = ['ca', 'tsas', 'tp', 'astd'];

        $return_arr = [
            'organization_id' => 'required',
            'bi_staff_email' => 'required|email|max:300',
            'bi_staff_fname' => 'required|string|max:100',
            'bi_staff_lname' => 'required|string|max:100',
            'bi_telephone' => 'required|digits:11',
            'bi_staff_gender' => "required|string|max:50|in:". implode($gender_arr, ','),
            'nomination_type' => "required|string|max:50|in:". implode($nomination_type, ','),
        ];

        if(auth()->user()->hasAnyRole(['bi-desk-officer', 'bi-hoi']) ){
            $return_arr['bi_submission_request_id'] = 'required|exists:tf_bi_submission_requests,id';
        }

        return $return_arr;
    }

    public function attributes() {
        return [
            'organization_id' => 'Organization Id',
            'bi_staff_email' => 'Beneficiary Staff Email',
            'bi_staff_fname' => 'Staff First Name',
            'bi_staff_lname' => 'Staff Last Name',
            'bi_telephone' => 'Staff Telephone',
            'bi_staff_gender' => 'Staff Gender',
            'nomination_type' => 'Type of Nomination',
            'bi_submission_request_id' => 'Nomination to one Submission Binding',
        ];
    }

}
