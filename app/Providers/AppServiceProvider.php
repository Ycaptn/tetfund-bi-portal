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
            'bi-admin'                  =>  [],
            'bi-desk-officer'           =>  [],
            'bi-astd-commitee-head'     =>  [],
            'bi-co-commitee-head'       =>  [],
            'bi-tp-commitee-head'       =>  [],
            'bi-tsas-commitee-head'     =>  [],
            'bi-astd-commitee-member'   =>  [],
            'bi-co-commitee-member'     =>  [],
            'bi-tp-commitee-member'     =>  [],
            'bi-tsas-commitee-member'   =>  [],
            'bi-ict'                    =>  [],
            'bi-lib'                    =>  [],
            'bi-works'                  =>  [],
            'bi-ppd'                    =>  [],
            'bi-staff'                  =>  [],
            'bi-student'                =>  [],
        ]);

        Schema::defaultStringLength(125);
    }
}
