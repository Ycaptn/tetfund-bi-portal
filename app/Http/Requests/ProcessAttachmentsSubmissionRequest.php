<?php

namespace App\Http\Requests;

use App\Http\Requests\AppBaseFormRequest;
use App\Models\Beneficiary;

class ProcessAttachmentsSubmissionRequest extends AppBaseFormRequest
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
        $array_to_return = ['organization_id' => 'required'];
        
        if(request()->checklist_input_fields != "") {
            
            $checklist_input_fields_arr = explode(',', request()->checklist_input_fields);
            
            foreach ($checklist_input_fields_arr as $checklist_input_name) {
                $array_to_return[strval($checklist_input_name)] = 'sometimes|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,dwg|max:102400';
            }

        }
        
        if (isset(request()->additional_attachment)) {
            $array_to_return['additional_attachment_name'] = 'required|string|max:190';
        }
        
        $array_to_return['additional_attachment'] = 'sometimes|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,dwg|max:102400';
        
        return $array_to_return;
    }

    public function attributes() {
        $array_to_return = array();
        
        if(request()->checklist_input_fields != "") {
          
            $checklist_input_fields_arr = explode(',', request()->checklist_input_fields);
            $counter = 1;
           
            foreach ($checklist_input_fields_arr as $checklist_input_name) {
                $array_to_return[strval($checklist_input_name)] = "Attachment With S/N ($counter)";
                $counter += 1;
            }

        }
       
        $array_to_return['additional_attachment_name'] = 'Additional Attachment Name';
        $array_to_return['additional_attachment'] = 'Additional Attachment';
       
        return $array_to_return;
    }
}
