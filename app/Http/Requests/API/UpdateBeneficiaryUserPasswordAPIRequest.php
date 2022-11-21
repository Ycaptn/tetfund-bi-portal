<?php

namespace App\Http\Requests\API;

use App\Http\Requests\AppBaseFormRequest;


class UpdateBeneficiaryUserPasswordAPIRequest extends AppBaseFormRequest
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
            'password' => 'required|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/',
            'confirm_password' => 'required|same:password',
        ];
    }

    public function attributes() {
        return [
            'organization_id' => 'Organization Id',
            'password' => 'New Password',
            'confirm_password' => 'Confirm Password',
        ];
    }

    public function messages() {
        return [
            'regex' => 'The :attributes must contain at least one numeric, lower/upper case and special character(s)'
        ];
    }

}
