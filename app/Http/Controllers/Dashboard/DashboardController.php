<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Hasob\FoundationCore\Models\User;
use Hasob\FoundationCore\Models\Department;
use Hasob\FoundationCore\Models\Organization;

use Hasob\FoundationCore\Controllers\BaseController;

class DashboardController extends BaseController
{
    

    public function index(Organization $org, Request $request){

        $current_user = Auth()->user();

        return view('dashboard.index')
                    ->with('organization', $org)
                    ->with('current_user', $current_user);
    }

    public function displayMonitoringDashboard(Organization $org, Request $request){

        $current_user = Auth()->user();

        return view('pages.monitoring.index')
                    ->with('organization', $org)
                    ->with('current_user', $current_user);
    }

    public function displayASTDNominationsDashboard(Organization $org, Request $request){

        $current_user = Auth()->user();

        return view('pages.astd_nominations.index')
                    ->with('organization', $org)
                    ->with('current_user', $current_user);
    }

    public function displayFundAvailabilityDashboard(Organization $org, Request $request){

        $current_user = Auth()->user();

        return view('pages.fund_availability.index')
                    ->with('organization', $org)
                    ->with('current_user', $current_user);
    }

    public function displayDeskOfficerAdminDashboard(Organization $org, Request $request){

        $current_user = Auth()->user();

        return view('pages.desk_officer.index')
                    ->with('organization', $org)
                    ->with('current_user', $current_user);
    }

    public function displayLibrarianAdminDashboard(Organization $org, Request $request){

        $current_user = Auth()->user();

        return view('pages.librarian.index')
                    ->with('organization', $org)
                    ->with('current_user', $current_user);
    }

    public function displayDirectorICTAdminDashboard(Organization $org, Request $request){

        $current_user = Auth()->user();

        return view('pages.director_ict.index')
                    ->with('organization', $org)
                    ->with('current_user', $current_user);
    }

    public function displayDirectorPIWorksAdminDashboard(Organization $org, Request $request){

        $current_user = Auth()->user();

        return view('pages.director_works.index')
                    ->with('organization', $org)
                    ->with('current_user', $current_user);
    }


}
