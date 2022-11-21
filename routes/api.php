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
                Route::resource('a_s_t_d_nominations', \App\Http\Controllers\API\ASTDNominationAPIController::class);
                Route::resource('t_p_nominations', \App\Http\Controllers\API\TPNominationAPIController::class);

                //Route::resource('c_a_nominations', \TETFund\ASTD\Controllers\API\CANominationAPIController::class);
                
                Route::get('/getAllInterventionLinesForSpecificType', [\App\Http\Controllers\API\SubmissionRequestAPIController::class, 'getAllInterventionLinesForSpecificType'])->name('getAllInterventionLinesForSpecificType');
                
                Route::resource('submission_requests', \App\Http\Controllers\API\SubmissionRequestAPIController::class);
            });

        });

    });
};

Route::group([], $orgRoutes);