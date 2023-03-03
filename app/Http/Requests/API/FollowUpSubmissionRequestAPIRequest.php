<?php

namespace App\Http\Requests\API;

use App\Http\Requests\AppBaseFormRequest;

class FollowUpSubmissionRequestAPIRequest extends AppBaseFormRequest {
    
    public function authorize() {
        return true;
    }

    public function rules() {
        return [
            'follow_up_submission_comment' => 'required|string||max:1000',
            'follow_up_submission_attachment' => 'required|file|mimes:pdf,doc,docx,jpg,png,jpeg|max:104800',
        ];

    }

    public function attributes() {
        return [
            'follow_up_submission_comment'=>'Follow-up Comment',
            'follow_up_submission_attachment'=>'Follow-up Attachment',
        ];
    }

}
