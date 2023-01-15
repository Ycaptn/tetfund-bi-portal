<?php

namespace App\Http\Requests\API;

use App\Models\CommitteeMeetingsMinutes;
use App\Http\Requests\AppBaseFormRequest;


class CommitteeMeetingsMinutesAPIRequest extends AppBaseFormRequest
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
            'nomination_type' => "required|string|max:5|in:tp,ca,tsas",
            'upload_additional_description' => 'nullable|string|min:2|max:255',
            'uploaded_minutes_of_meeting' => 'required|file|mimes:pdf|max:5240',
        ];
    }

    public function messages() {
        return [
            'uploaded_minutes_of_meeting.required' => 'The :attribute file is required',
        ];
    }

    public function attributes() {
        return [
            'nomination_type' => 'Nomination Type',
            'upload_additional_description' => 'Additional Description',
            'uploaded_minutes_of_meeting' => 'Upload Minutes of Meeting',
        ];
    }

}
