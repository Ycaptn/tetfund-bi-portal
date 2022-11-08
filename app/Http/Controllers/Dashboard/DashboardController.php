<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Hasob\FoundationCore\Models\User;
use Hasob\FoundationCore\Models\Department;
use Hasob\FoundationCore\Models\Organization;
use App\Models\BeneficiaryMember;

use Hasob\FoundationCore\Controllers\BaseController;
use App\Managers\TETFundServer;

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
    }

    public function displayFundAvailabilityDashboard(Organization $org, Request $request) {

        $current_user = Auth()->user();
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();
        $bi_beneficiary = $beneficiary_member->beneficiary;
        $tf_beneficiary_id = $bi_beneficiary->tf_iterum_portal_key_id;

        $selected_year = date('Y');
        if (isset($request->year) &&  $request->year!=null && is_numeric( $request->year)) {
            $selected_year = $request->year;
        }

        //Get the funding data for the selected year.
        $tETFundServer = new TETFundServer();   /* server class constructor */
        $funding = $tETFundServer->getFundAvailabilityData($tf_beneficiary_id, null, [$selected_year]);

        return view('pages.fund_availability.index')
                ->with('organization', $org)
                ->with("funding", (array) $funding)
                ->with("selected_year", $selected_year) 
                ->with("beneficiary", $bi_beneficiary)
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
