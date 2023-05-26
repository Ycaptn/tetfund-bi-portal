<?php

namespace App\Http\Requests\API;

use App\Models\Beneficiary;
use App\Http\Requests\AppBaseFormRequest;
use Spatie\Permission\Models\Role;


class UpdateBeneficiaryMemberAPIRequest extends AppBaseFormRequest
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
        $gender_arr = ['male', 'female']; // possible genders
        $roles_set = false; //role flag

        // array of errors to be retured
        $return_arr = [
            'organization_id' => 'required',
            'bi_staff_email' => 'required|email|max:300',
            'bi_staff_fname' => 'required|string|max:100',
            'bi_staff_lname' => 'required|string|max:100',
            'bi_telephone' => 'required|digits:11',
            'bi_staff_gender' => "required|string|max:50|in:". implode($gender_arr, ','),
            'beneficiary_id' => 'required|string|exists:tf_bi_portal_beneficiaries,id|max:300',
            'bi_grade_level' => 'required|numeric',
            'bi_member_type' => 'required',
        ];

        $allRoles = Role::where('guard_name', 'web')
                    ->where('name', '!=', 'admin')
                    ->where('name', 'like', 'bi-%')
                    ->pluck('name');

        // checking if any role is set
        if(count($allRoles) > 0) {
            foreach($allRoles as $role) {
                if (isset(request()->{'userRole_'.$role}) && request()->{'userRole_'.$role} == 'on') $roles_set = true;
            }
        }

        // append error if no role is selected
        if ($roles_set == false) {
            $return_arr['user_roles'] = 'required|string|max:100';
        }

        // return errors
        return $return_arr;
    }

    public function attributes() {
        return [
            'organization_id' => 'Organization Id',
            'bi_staff_email' => 'Beneficiary User Email',
            'bi_staff_fname' => 'First Name',
            'bi_staff_lname' => 'Last Name',
            'bi_telephone' => 'Telephone',
            'bi_staff_gender' => 'Gender',
            'beneficiary_id' => 'Beneficiary',
            'bi_grade_level' => 'Grade Level',
            'bi_member_type' => 'Member Type',
        ];
    }

    public function messages() {
        return [
            'user_roles.required' => 'You must select at-least One (1) or more User Role(s) to proceed.',
        ];
    }

}
