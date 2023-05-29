<?php

namespace App\Http\Requests\API;

use App\Models\SubmissionRequest;
use App\Http\Requests\AppBaseFormRequest;


class ReprioritizeSubmissionRequestAPIRequest extends AppBaseFormRequest
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
        $years = ['0'];
        for ($i=0; $i < 6; $i++) { 
            array_push($years, date("Y")-$i);
        }

        $return_arr = [
            'reprioritize_amount_requested' => 'required|numeric|min:0|max:100000000000',
            'reprioritize_intervention_year1' => "nullable|numeric|in:". implode(',', $years),
            'reprioritize_intervention_year2' => "nullable|numeric|in:". implode(',', $years),
            'reprioritize_intervention_year3' => "nullable|numeric|in:". implode(',', $years),
            'reprioritize_intervention_year4' => "nullable|numeric|in:". implode(',', $years),
        ];

        if (request()->reprioritize_intervention_year1==null && request()->reprioritize_intervention_year2==null && request()->reprioritize_intervention_year3==null && request()->reprioritize_intervention_year4==null) {
            $return_arr['intervention_years'] = 'required';
        }
        
        $return_arr['reprioritize_submission_comment'] = 'required|string||max:1000';
        $return_arr['reprioritize_submission_attachment'] = 'required|file|mimes:pdf,doc,docx,jpg,png,jpeg|max:104800';

        return$return_arr;
    }

    public function attributes() {
        return [
            'reprioritize_amount_requested'=>'Requested Amount',
            'reprioritize_intervention_year1'=>'Intervention Year 1',
            'reprioritize_intervention_year2'=>'Intervention Year 2',
            'reprioritize_intervention_year3'=>'Intervention Year 3',
            'reprioritize_intervention_year4'=>'Intervention Year 4',            
            'intervention_years'=>'Intervention Year(s)',
            'reprioritize_submission_comment'=>'Comment',
            'reprioritize_submission_attachment'=>'Attachment',
        ];
    }

    public function messages() {
        return [
            'intervention_years.required' => 'Selected atleast one (1) or more :attribute to proceed.'
        ];
    }


}
