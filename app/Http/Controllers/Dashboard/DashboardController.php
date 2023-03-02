<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

use App\Managers\TETFundServer;
use App\Models\BeneficiaryMember;
use App\Models\SubmissionRequest;

use Hasob\FoundationCore\Models\User;
use Hasob\FoundationCore\Models\Department;
use Hasob\FoundationCore\Models\Organization;
use Hasob\FoundationCore\Models\Attachment;
use Hasob\FoundationCore\View\Components\CardDataView;
use Hasob\FoundationCore\Controllers\BaseController;

use App\Http\Requests\CreateBeneficiaryRequest;
use App\Http\Requests\UpdateBeneficiaryRequest;
use App\DataTables\BeneficiaryMemberDatatable;
use App\DataTables\MonitoringRequestDataTable;
use Spatie\Permission\Models\Role;


class DashboardController extends BaseController
{
    

    public function index(Organization $org, Request $request){

        $current_user = Auth()->user();

        return view('dashboard.index')
                    ->with('organization', $org)
                    ->with('current_user', $current_user);
    }

    public function displayMonitoringDashboard(Organization $org, Request $request)
    {
        $current_user = Auth()->user();
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();

        $tetFundServer = new TETFundServer();
        $pay_load = ['_method'=>'GET', 'beneficiary_type'=>$beneficiary_member->beneficiary->type ?? null];
        $intervention_types_server_response = $tetFundServer->get_all_data_list_from_server('tetfund-ben-mgt-api/interventions', $pay_load);

        $intervention_lines = [];
        foreach($intervention_types_server_response as $idx=>$item){
            $intervention_lines [$item->id]= $item->name;
        }

        $cdv_submission_requests = new CardDataView(SubmissionRequest::class, "pages.monitoring.card_view_item", $intervention_lines);
        $cdv_submission_requests->setDataQuery([
                    'organization_id' => $org->id,
                    'beneficiary_id' => $beneficiary_member->beneficiary_id, 
                    'is_monitoring_request' => true 
                ])
                ->addDataGroup('All','deleted_at',null)
                ->addDataGroup('Not Submitted','status','not-submitted')
                ->addDataGroup('Submitted','status','submitted')
                ->addDataGroup('Approved','status','approved')
                ->enableSearch(true)
                ->addDataOrder('created_at', 'DESC')
                ->enablePagination(true);

        if (request()->expectsJson()){
            return $cdv_submission_requests->render();
        }

        return view('pages.monitoring.card_view_index')
                    ->with('organization', $org)
                    ->with('current_user', $current_user)
                    ->with('cdv_submission_requests', $cdv_submission_requests);
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

    public function displayDeskOfficerAdminDashboard(Organization $org, BeneficiaryMemberDatatable $beneficiaryMembersDatatable) {
        $current_user = Auth()->user();

        /** @var BeneficiaryMember $beneficiary_member */
        $beneficiary_member = BeneficiaryMember::where('beneficiary_user_id', $current_user->id)->first();
        
        if (empty($beneficiary_member) || $beneficiary_member->beneficiary == null) {
            //Flash::error('BeneficiaryMember not found');
            return redirect(route('tf-bi-portal.beneficiaries.index'));
        }

        $allRoles = Role::where('guard_name', 'web')
                    ->where('name', '!=', 'admin')
                    ->where('name', '!=', 'BI-desk-officer')
                    ->where('name', 'like', 'bi-%')
                    ->pluck('name');
        
        return $beneficiaryMembersDatatable->with('beneficiary_id', $beneficiary_member->beneficiary->id)
                ->render('tf-bi-portal::pages.desk_officer.index', [
                    'beneficiary'=>$beneficiary_member->beneficiary,
                    'geo_zone_list'=>BaseController::geoZoneList(),
                    'states_list'=>BaseController::statesList(),
                    'organization'=>$org,
                    'current_user'=>$current_user,
                    'roles'=>$allRoles
                ]);
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
