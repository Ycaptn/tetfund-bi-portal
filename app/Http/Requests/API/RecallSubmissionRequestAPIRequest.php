<?php

namespace App\Http\Requests\API;

use App\Http\Requests\AppBaseFormRequest;

class RecallSubmissionRequestAPIRequest extends AppBaseFormRequest {
    
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'recall_submission_comment' => 'required|string||max:1000',
            'recall_submission_attachment' => 'required|file|mimes:pdf,doc,docx,jpg,png,jpeg|max:104800',
        ];

    }

    public function attributes() {
        return [
            'recall_submission_comment'=>'Recall Comment',
            'recall_submission_attachment'=>'Recall Letter',
        ];
    }
    public function messages() {
        return [
            'recall_submission_attachment.required' => 'The :attribute attachment field is required.'
        ];
    }

}
