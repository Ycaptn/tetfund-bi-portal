<?php

namespace App\Http\Requests\API;

use App\Models\SubmissionRequest;
use App\Http\Requests\AppBaseFormRequest;


class CreateSubmissionRequestAPIRequest extends AppBaseFormRequest
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
            //'organization_id' => 'required',
            'tf_iterum_intervention_line_key_id' => 'required|string|max:300',
            'title' => 'required|string|max:300',
            'intervention_year1' => "nullable|numeric|in:". implode($years, ','),
            'intervention_year2' => "nullable|numeric|in:". implode($years, ','),
            'intervention_year3' => "nullable|numeric|in:". implode($years, ','),
            'intervention_year4' => "nullable|numeric|in:". implode($years, ','),

            //'status' => 'nullable|max:100',
            //'display_ordinal' => 'nullable|min:0|max:365',
            //'requesting_user_id' => 'required',
            //'beneficiary_id' => 'required',
            //'tf_iterum_portal_request_status' => 'required',
            //'tf_iterum_portal_response_meta_data' => 'max:1000'
        ];

        // reqiure proposed request date field when submission is monitoring request
        if (request()->has('is_monitoring_request') && request()->is_monitoring_request == true) {
            $return_arr['type'] = 'required|string|max:300';
            $return_arr['proposed_request_date'] = 'required|date|after:today';
            $return_arr['optional_attachment'] = 'sometimes|file|mimes:pdf,doc,docx,jpg,png,jpeg|max:52400';
        }

        if (request()->intervention_year1==null && request()->intervention_year2==null && request()->intervention_year3==null && request()->intervention_year4==null) {
            $return_arr['intervention_years'] = 'required';
        }
        
        $return_arr['amount_requested'] = 'required|numeric|min:0|max:100000000000';

        return$return_arr;
    }

    public function attributes() {
        return [
            'intervention_type'=>'Intervention Type',
            'tf_iterum_intervention_line_key_id'=>'Intervention Line',
            'title'=>'Project Title',
            'intervention_year1'=>'Intervention Year 1',
            'intervention_year2'=>'Intervention Year 2',
            'intervention_year3'=>'Intervention Year 3',
            'intervention_year4'=>'Intervention Year 4',            
            'amount_requested'=>'Requested Amount',
            'intervention_years'=>'Intervention Year(s)',
            'type'=>'Type of Monitoring Request',
            'proposed_request_date'=>'Proposed Monitoring Date',
            'optional_attachment'=>'Optional Attachment',
        ];
    }

    public function messages() {
        return [
            'intervention_years.required' => 'Selected atleast one (1) or more :attribute to proceed.'
        ];
    }


}
