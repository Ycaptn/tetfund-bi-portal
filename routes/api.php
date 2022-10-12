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
                Route::get('synchronize_beneficiary_list', [\App\Http\Controllers\API\BeneficiaryAPIController::class, 'synchronize_beneficiary_list'])->name('synchronize_beneficiary_list');
                
                Route::resource('submission_requests', \App\Http\Controllers\API\SubmissionRequestAPIController::class);
            });

        });

    });
};

Route::group([], $orgRoutes);