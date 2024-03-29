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

        $return_arr = [
            'organization_id' => 'required',
            'bi_staff_email' => 'required|email|max:300',
            'bi_staff_fname' => 'required|string|max:100',
            'bi_staff_lname' => 'required|string|max:100',
            'bi_telephone' => 'required|digits:11',
            'bi_staff_gender' => "required|string|max:50|in:male,female",
            'nomination_type' => "required|string|max:50|in:ca,tsas,tp",
        ];

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
        ];
    }

    public function messages() {
        return [
            'bi_submission_request_id.required' => 'The Bind Nomination to one Submission selection is required.',
            'gender.in' => 'Invalid gender. Please update your profile',
            'telephone.digits' => 'Invalid telephone number. Please update your profile',
        ];
    }


}
