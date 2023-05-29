<?php

namespace App\Http\Requests\API;

use App\Models\SubmissionRequest;
use App\Http\Requests\AppBaseFormRequest;
use Illuminate\Support\Facades\Validator;


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

        $returned_arr = [
            //'organization_id' => 'required',
            'tf_iterum_intervention_line_key_id' => 'required|string|max:300',
            'title' => 'required|string|max:300',
            'intervention_year1' => "nullable|numeric|in:". implode(',', $years),
            'intervention_year2' => "nullable|numeric|in:". implode(',', $years),
            'intervention_year3' => "nullable|numeric|in:". implode(',', $years),
            'intervention_year4' => "nullable|numeric|in:". implode(',', $years),
        ];

        // reqiure proposed request date field when submission is monitoring request
        if (request()->has('is_monitoring_request') && request()->is_monitoring_request == true) {
            $returned_arr['type'] = 'required|string|max:300';
            $returned_arr['proposed_request_date'] = 'required|date|after:today';
            $returned_arr['optional_attachment'] = 'sometimes|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,png,jpeg,dwg|max:52400';
        }

        if (request()->intervention_year1==null && request()->intervention_year2==null && request()->intervention_year3==null && request()->intervention_year4==null) {
            $returned_arr['intervention_years'] = 'required';
        }
        
        $returned_arr['amount_requested'] = 'required|numeric|min:0|max:100000000000';

        // required validations if request contains ongoinging submission_request_stage and file_attachments
        if (request()->has('ongoing_submission_stage') && !empty(request()->ongoing_submission_stage)) {
            $valid_ongoing_submission_stages = [
                '1st_Tranche_Payment', '2nd_Tranche_Payment', 'Final_Tranche_Payment', 'Monitoring_Request'
            ];

            $returned_arr['ongoing_submission_stage'] = "required|string|max:50|in:". implode(',', $valid_ongoing_submission_stages);

            if (request()->hasFile('file_attachments') && count(request()->file_attachments)>0) {
                $returned_arr['file_attachments.*'] = 'required|file|mimes:pdf|max:100000';
            } else {
                $returned_arr['file_attachments'] = 'required';
            }
        }

        return$returned_arr;
    }

    public function attributes() {
        $arr_returned = [
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
                'ongoing_submission_stage'=>'Ongoing Submission Request Stage',
                'file_attachments'=>'File Attachments',
        ];

        if (request()->file('file_attachments') && count(request()->file_attachments) > 0) {
            $total_attachments = count(request()->file_attachments);

            for ($i=0; $i<$total_attachments; $i++) {
                $column_name = 'file_attachments.' . $i;
                $arr_returned[$column_name] = $this->ordinal($i+1) . ' File Attached';
            }

            $arr_returned['file_attachments.*'] = 'File Attachments';
        }

        return $arr_returned;
    }

    public function messages() {
        return [
            'intervention_years.required' => 'Selected atleast one (1) or more :attribute to proceed.'
        ];
    }

    function ordinal($number) {
        $suffixes = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
        if (($number % 100) >= 11 && ($number % 100) <= 13) {
            return $number . 'th';
        } else {
            return $number . $suffixes[$number % 10];
        }
    }


}
