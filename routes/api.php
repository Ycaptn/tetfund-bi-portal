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
            
        });

    });
};

Route::group([], $orgRoutes);