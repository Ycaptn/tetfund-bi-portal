<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Route;


$orgRoutes = function() {
    Route::group([
        'middleware' => \Hasob\FoundationCore\Middleware\IdentifyOrganization::class,
    ], function () {

        //Frontend routes
        Route::get('/', [\App\Http\Controllers\Frontend\FrontendController::class, 'displayHome'])->name('home');
        Route::post('/', [\App\Http\Controllers\Frontend\FrontendController::class, 'processRegistration'])->name('registration');


        \FoundationCore::public_routes();
        Auth::routes();

        Route::middleware(['auth'])->group(function () {

            //Package Routes
            \FoundationCore::routes();

            //Dashboard Routes
            Route::get('/dashboard', [\App\Http\Controllers\Dashboard\DashboardController::class, 'index'])->name('dashboard');
            
            Route::name('tf-bi-portal.')->prefix('tf-bi-portal')->group(function(){


                Route::get('/monitoring', [\App\Http\Controllers\Dashboard\DashboardController::class, 'displayMonitoringDashboard'])->name('monitoring');
                Route::get('/fund-availability', [\App\Http\Controllers\Dashboard\DashboardController::class, 'displayFundAvailabilityDashboard'])->name('fund-availability');
                Route::get('/desk-officer', [\App\Http\Controllers\Dashboard\DashboardController::class, 'displayDeskOfficerAdminDashboard'])->name('desk-officer');
                Route::get('/librarian', [\App\Http\Controllers\Dashboard\DashboardController::class, 'displayLibrarianAdminDashboard'])->name('librarian');
                Route::get('/dict', [\App\Http\Controllers\Dashboard\DashboardController::class, 'displayDirectorICTAdminDashboard'])->name('dict');
                Route::get('/dworks', [\App\Http\Controllers\Dashboard\DashboardController::class, 'displayDirectorPIWorksAdminDashboard'])->name('dworks');
                Route::get('/preview-attachement/{id}', [\App\Http\Controllers\Dashboard\DashboardController::class, 'displayAttachmentDashboard'])->name('preview-attachement');

                Route::resource('nomination_requests', \App\Http\Controllers\Models\NominationRequestController::class);
                Route::resource('t_s_a_s_nominations', \App\Http\Controllers\Models\TSASNominationController::class);
                Route::resource('t_p_nominations', \App\Http\Controllers\Models\TPNominationController::class);

                Route::resource('beneficiaries', \App\Http\Controllers\Models\BeneficiaryController::class);
                Route::post('/processSubmissionRequestToTFPortal', [\App\Http\Controllers\Models\SubmissionRequestController::class, 'processSubmissionRequestToTFPortal'])->name('submissionRequests.processSubmissionRequestToTFPortal');
                Route::post('/processSubmissionRequestAttachement', [\App\Http\Controllers\Models\SubmissionRequestController::class, 'processSubmissionRequestAttachement'])->name('processSubmissionRequestAttachement');
                Route::resource('submissionRequests', \App\Http\Controllers\Models\SubmissionRequestController::class);
            });

        });

    });
};


Route::group([], $orgRoutes);