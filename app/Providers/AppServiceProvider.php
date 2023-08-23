<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

use Hasob\FoundationCore\Models\Setting;
use Hasob\FoundationCore\Models\Organization;
use Hasob\FoundationCore\Managers\OrganizationManager;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }



    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Current Organization
        $org = \FoundationCore::current_organization();

        //Roles in this application with their permissions.
        \FoundationCore::register_roles([
            'BI-admin'                          =>  [],
            'BI-desk-officer'                   =>  [],
            'BI-head-of-institution'            =>  [],
            'BI-CA-committee-head'              =>  [],
            'BI-TP-committee-head'              =>  [],
            'BI-TSAS-committee-head'            =>  [],
            'BI-CA-committee-member'            =>  [],
            'BI-TP-committee-member'            =>  [],
            'BI-TSAS-committee-member'          =>  [],
            'BI-ict'                            =>  [],
            'BI-librarian'                      =>  [],
            'BI-works'                          =>  [],
            'BI-physical-planning-dept'         =>  [],
            'BI-staff'                          =>  [],
            'BI-student'                        =>  [],
            'BI-astd-desk-officer'              =>  [],
        ]);

        Schema::defaultStringLength(125);
    }
}
