<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


$orgRoutes = function() {

    Route::group([
        'middleware' => \Hasob\FoundationCore\Middleware\IdentifyOrganization::class,
    ], function () {


        \FoundationCore::api_public_routes();

        Route::middleware(['auth:sanctum'])->group(function () {

            \FoundationCore::api_routes();

            Route::name('tf-bi-portal-api.')->prefix('tf-bi-portal-api')->group(function(){
                Route::resource('beneficiaries', \App\Http\Controllers\API\BeneficiaryAPIController::class);
                Route::resource('committee_meeting_minutes', \App\Http\Controllers\API\CommitteeMeetingsMinutesController::class);
                
                // sychronization processing
                Route::get('synchronize_beneficiary_list', [\App\Http\Controllers\API\BeneficiaryAPIController::class, 'synchronize_beneficiary_list'])->name('synchronize_beneficiary_list');
               
                // beneficiary member management
                Route::post('store_beneficiary_member', [\App\Http\Controllers\API\BeneficiaryAPIController::class, 'store_beneficiary_member'])->name('store_beneficiary_member');
                Route::get('show_beneficiary_member/{id}', [\App\Http\Controllers\API\BeneficiaryAPIController::class, 'show_beneficiary_member'])->name('show_beneficiary_member');
                Route::put('update_beneficiary_member/{id}', [\App\Http\Controllers\API\BeneficiaryAPIController::class, 'update_beneficiary_member'])->name('update_beneficiary_member');
                Route::put('reset_password_beneficiary_member/{id}', [\App\Http\Controllers\API\BeneficiaryAPIController::class, 'reset_password_beneficiary_member'])->name('reset_password_beneficiary_member');
                Route::get('show_beneficiary_member/{id}', [\App\Http\Controllers\API\BeneficiaryAPIController::class, 'show_beneficiary_member'])->name('show_beneficiary_member');
                Route::get('enable_disable_beneficiary_member/{id}', [\App\Http\Controllers\API\BeneficiaryAPIController::class, 'enable_disable_beneficiary_member'])->name('enable_disable_beneficiary_member');
                Route::delete('delete_beneficiary_member/{id}', [\App\Http\Controllers\API\BeneficiaryAPIController::class, 'delete_beneficiary_member'])->name('delete_beneficiary_member');
                
                //submission_requests
                Route::post('submission_requests/request_actions/{id}', [\App\Http\Controllers\API\NominationRequestAPIController::class, 'request_actions'])->name('submission_requests.request_actions');

                
                Route::resource('nomination_requests', \App\Http\Controllers\API\NominationRequestAPIController::class);
                Route::get('nomination_requests/show_selected_email/{email}', [\App\Http\Controllers\API\NominationRequestAPIController::class, 'show_selected_email'])->name('nomination_requests.show_selected_email');
                Route::put('nomination_requests/process_forward_details/{id}', [\App\Http\Controllers\API\NominationRequestAPIController::class, 'process_forward_details'])->name('nomination_requests.process_forward_details');
                Route::put('nomination_requests/process_forward_all_details/{itemIdType}', [\App\Http\Controllers\API\NominationRequestAPIController::class, 'process_forward_all_details'])->name('nomination_requests.process_forward_all_details');
                Route::post('nomination_requests/process_committee_member_vote/{id}', [\App\Http\Controllers\API\NominationRequestAPIController::class, 'process_committee_member_vote'])->name('nomination_requests.process_committee_member_vote');
                Route::post('nomination_requests/store_nomination_request_and_details', [\App\Http\Controllers\API\NominationRequestAPIController::class, 'store_nomination_request_and_details'])->name('nomination_requests.store_nomination_request_and_details');
                Route::post('nomination_requests/process_committee_head_consideration/{id}', [\App\Http\Controllers\API\NominationRequestAPIController::class, 'process_committee_head_consideration'])->name('nomination_requests.process_committee_head_consideration');
                Route::post('nomination_requests/process_nomination_details_approval_by_hoi/{id}', [\App\Http\Controllers\API\NominationRequestAPIController::class, 'process_nomination_details_approval_by_hoi'])->name('nomination_requests.process_nomination_details_approval_by_hoi');

                Route::resource('t_s_a_s_nominations', \App\Http\Controllers\API\TSASNominationAPIController::class);
                Route::resource('c_a_nominations', \App\Http\Controllers\API\CANominationAPIController::class);
                Route::resource('t_p_nominations', \App\Http\Controllers\API\TPNominationAPIController::class);
                
                Route::resource('submission_requests', \App\Http\Controllers\API\SubmissionRequestAPIController::class);
                Route::get('submission_requests/get_all_related_nomination_request/{type}', [\App\Http\Controllers\API\SubmissionRequestAPIController::class, 'get_all_related_nomination_request'])->name('submission_requests.get_all_related_nomination_request');
                Route::post('submission_requests/process_m_r_to_tetfund/{id}', [\App\Http\Controllers\API\SubmissionRequestAPIController::class, 'process_m_r_to_tetfund'])->name('submission_requests.process_m_r_to_tetfund');
                Route::post('submission_requests/clarification_response', [\App\Http\Controllers\API\SubmissionRequestAPIController::class, 'clarification_response'])->name('submission_requests.clarification_response');
                Route::post('submission_requests/reprioritize/{id}', [\App\Http\Controllers\API\SubmissionRequestAPIController::class, 'reprioritize'])->name('submission_requests.reprioritize');
                Route::post('submission_requests/{submission_request_id}', [\App\Http\Controllers\API\SubmissionRequestAPIController::class, 'processFollowUpSubmission'])->name('submission_requests.follow_up_submission');
            });

        });

    });
};

Route::group([], $orgRoutes);