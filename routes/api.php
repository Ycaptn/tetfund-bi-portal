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

                
                Route::resource('a_s_t_d_nominations', \App\Http\Controllers\API\ASTDNominationAPIController::class);
                //Route::resource('t_p_nominations', \TETFund\ASTD\Controllers\API\TPNominationAPIController::class);
                //Route::resource('c_a_nominations', \TETFund\ASTD\Controllers\API\CANominationAPIController::class);
                //Route::resource('conferences', \TETFund\ASTD\Controllers\API\ConferenceAPIController::class);
                Route::resource('countries', \App\Http\Controllers\API\CountryAPIController::class);
                Route::resource('institutions', \App\Http\Controllers\API\InstitutionAPIController::class);
                
                Route::resource('submission_requests', \App\Http\Controllers\API\SubmissionRequestAPIController::class);
            });

        });

    });
};

Route::group([], $orgRoutes);