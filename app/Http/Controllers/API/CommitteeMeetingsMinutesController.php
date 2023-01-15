<?php

namespace App\Http\Controllers\API;

use DB;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Beneficiary;
use App\Models\BeneficiaryMember;
use App\Models\SubmissionRequest;
use App\Models\CommitteeMeetingsMinutes;
use App\Http\Requests\API\CommitteeMeetingsMinutesAPIRequest;
use App\Http\Requests\API\CommitteeMeetingsMinutesUpdateAPIRequest;

use Hasob\FoundationCore\Models\User;
use Hasob\FoundationCore\Models\Attachment;
use Hasob\FoundationCore\Models\Organization;
use Hasob\FoundationCore\Traits\ApiResponder;
use Hasob\FoundationCore\Controllers\BaseController as AppBaseController;

use Spatie\Permission\Models\Role;

class CommitteeMeetingsMinutesController extends AppBaseController
{
    use ApiResponder;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Organization $org)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Organization $org, CommitteeMeetingsMinutesAPIRequest $request) {
        $current_user = auth()->user();        
        
        $beneficiary = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first()->beneficiary ?? null;

        $mostRecentMinutes = CommitteeMeetingsMinutes::where('usage_status', 'not-used')
                    ->where('nomination_type', $request->nomination_type)
                    ->where('beneficiary_id', optional($beneficiary)->id)
                    ->latest('created_at')
                    ->first();

        if (empty($mostRecentMinutes)) {
            $mostRecentMinutes = new CommitteeMeetingsMinutes();
        }

        $mostRecentMinutes->organization_id = $current_user->organization_id;
        $mostRecentMinutes->user_id = $current_user->id;
        $mostRecentMinutes->beneficiary_id = optional($beneficiary)->id;
        $mostRecentMinutes->nomination_type = $request->nomination_type;
        $mostRecentMinutes->description = $request->upload_additional_description ?? null;
        $mostRecentMinutes->save();

        // attaching minutes of meeting
        $label = strtoupper($request->nomination_type) . '-Nomination Committee minutes of meeting';
        
        $description_surfix = (isset($request->upload_additional_description) && $request->upload_additional_description != null) ? " --({$request->upload_additional_description})" : '';
        
        $discription = $label . ' document.' . $description_surfix;

        //looking for old initially attached document
        $attachment = $mostRecentMinutes->get_specific_attachment($mostRecentMinutes->id, $label); 

        // delete old initially attached document
        if ($attachment != null) {
            $mostRecentMinutes->delete_attachment($label); 
        }

        $attachment_response = $mostRecentMinutes->attach($current_user, $label, $discription, $request->uploaded_minutes_of_meeting);

        if (!empty($attachment_response)) {
            return $this->sendResponse($attachment_response, $label.' successfully uploaded.');
        }

        return $this->sendError('An error was encountered while processing upload!');

    }

    
    public function show(Organization $org, Request $request, $nomination_type) {
        $current_user = auth()->user();        
        
        $beneficiary = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first()->beneficiary ?? null;

        $mostRecentMinutes = CommitteeMeetingsMinutes::with('user')
                    ->with('attachables.attachment')
                    ->where('usage_status', 'not-used')
                    ->where('nomination_type', $nomination_type)
                    ->where('beneficiary_id', optional($beneficiary)->id)
                    ->latest('created_at')
                    ->first();

        if (empty($mostRecentMinutes)) {
            return null;
        }

        $label = strtoupper($nomination_type) . '-Nomination Committee minutes of meeting';
        return $this->sendResponse($mostRecentMinutes->toArray(), $label.' retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CommitteeMeetingsMinutes  $committeeMeetingsMinutes
     * @return \Illuminate\Http\Response
     */
    public function update(Organization $org, CommitteeMeetingsMinutesUpdateAPIRequest $request, $submission_request_id) {
        $submission_request = SubmissionRequest::find($submission_request_id);

        $current_user = auth()->user();
        $beneficiary = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first()->beneficiary ?? null;

        $mostRecentMinutes = CommitteeMeetingsMinutes::with('user')
            ->with('attachables.attachment')
            ->where('usage_status', 'not-used')
            ->where('nomination_type', $request->nomination_type ?? '')
            ->where('beneficiary_id', optional($beneficiary)->id)
            ->latest('created_at')
            ->first();

        if (empty($mostRecentMinutes)) {
            return $this->sendError('No recently uploaded/un-used minutes of meeting was found!');
        }

        $label = $request->checklist_item_name;
        $discription = 'This Document Contains the ' . $label ;

        //looking for old submission request minutes of meeting document
        $submission_request_attachment = $submission_request->get_specific_attachment($submission_request->id, $label); 

        // delete old submission request minutes of meeting document
        if ($submission_request_attachment != null) {
            $submission_request->delete_attachment($label); 
        }

        // label forr minutes of meeting uploaded
        $most_recent_minutes_label = strtoupper($request->nomination_type) . '-Nomination Committee minutes of meeting';
        
        // searching for uploaded attachement
        $most_recent_minutes_attachment = $mostRecentMinutes->get_specific_attachment($mostRecentMinutes->id, $most_recent_minutes_label);

        // creating attachment record
        $attachment_created = new Attachment();
        $attachment_created->path = $most_recent_minutes_attachment->path;
        $attachment_created->label = $label;
        $attachment_created->organization_id = $current_user->organization_id;
        $attachment_created->uploader_user_id = $current_user->id;
        $attachment_created->description = $discription;
        $attachment_created->file_type = $most_recent_minutes_attachment->file_type;
        $attachment_created->storage_driver = $most_recent_minutes_attachment->storage_driver;
        $attachment_created->save();

        // creating attachable record
        $attachable_created = $submission_request->create_attachable($current_user, $attachment_created);
        if ($attachable_created == null || empty($attachable_created)) {
            return $this->sendError('Error encountered while created attachable record');
        }

        return $this->sendSuccess($most_recent_minutes_label . ' successfully used.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CommitteeMeetingsMinutes  $committeeMeetingsMinutes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organization $org, CommitteeMeetingsMinutes $committeeMeetingsMinutes)
    {
        //
    }
}
