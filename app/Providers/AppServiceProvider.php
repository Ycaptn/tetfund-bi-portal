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
            'bi-admin'                          =>  [],
            'bi-desk-officer'                   =>  [],
            'bi-head-of-institution'            =>  [],
            'bi-astd-committee-head'            =>  [],
            'bi-ca-committee-head'              =>  [],
            'bi-tp-committee-head'              =>  [],
            'bi-tsas-committee-head'            =>  [],
            'bi-astd-committee-member'          =>  [],
            'bi-ca-committee-member'            =>  [],
            'bi-tp-committee-member'            =>  [],
            'bi-tsas-committee-member'          =>  [],
            'bi-ict'                            =>  [],
            'bi-librarian'                      =>  [],
            'bi-works'                          =>  [],
            'bi-physical-planning-department'   =>  [],
            'bi-staff'                          =>  [],
            'bi-student'                        =>  [],
        ]);

        Schema::defaultStringLength(125);
    }
}
