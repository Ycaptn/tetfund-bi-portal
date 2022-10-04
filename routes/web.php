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
            \FinanceAudit::routes();

            //Dashboard Routes
            Route::get('/dashboard', [\App\Http\Controllers\Dashboard\DashboardController::class, 'index'])->name('dashboard');
            
        });

    });
};


Route::group([], $orgRoutes);