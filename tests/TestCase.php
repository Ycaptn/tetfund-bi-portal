<?php

namespace Tests;


use Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Testing\WithFaker;

use Hasob\FoundationCore\Models\User;
use Hasob\FoundationCore\Models\Attachment;
use Hasob\FoundationCore\Models\Department;
use Hasob\FoundationCore\Models\Organization;
use Hasob\FoundationCore\Models\Ledger;
use TETFund\BeneficiaryMgt\Models\Beneficiary;
use TETFund\BeneficiaryMgt\Models\BeneficiaryCommunication;
use TETFund\BeneficiaryMgt\Models\Communication;
use TETFund\BeneficiaryMgt\Models\Intervention;
use TETFund\BeneficiaryMgt\Models\InterventionBeneficiaryType;
use TETFund\MonitoringEvaluation\Models\MonitoringRequest;
use TETFund\MonitoringEvaluation\Models\MonitoringSchedule;
use TETFund\MonitoringEvaluation\Models\MonitoringRequestCommunication;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

use Laravel\Sanctum\Sanctum;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use WithFaker;
    use DatabaseMigrations;

    protected $test_org;
    protected $test_user;
    protected $test_dept;
    protected $faker;
    public $beneficiaryData;
    public $beneficiaryMembershipData;
    public $beneficiaryPaymentDetailData;
    public $interventionData;
    public $interventionBeneficiaryTypeData;
    public $allocatedFundData;
    public $allocationSharingCriteriaData;
    public $allocationTemplateData;
    public $allocationUtilizationCriteriaData;
    public $communicationData;
    public $beneficiaryCommunicationData;
    public $monitoring_request_data;
    public $monitoring_schedule_data;
    public $monitoring_schedule_item_data;
    public $monitoring_request_communication_data;
    public $monitoring_request_report_data;
    public $fa_audit_queries_data;
    public $fa_payment_vouchers_data;


    protected function setUp(): void
    {
        parent::setUp();
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // create roles and permissions
        $roleWithPermissions = [
            'beneficiary-admin'     =>  [],
            'beneficiary-staff'       =>  [],
            'consultant' => [],
            'tetfund-me' => [],
            'admin' => []
        ];

        foreach ($roleWithPermissions as $role=>$permissions) {

            try{
                $dbRole = Role::findByName($role);
            }catch(RoleDoesNotExist $e) {
                $dbRole = Role::create(['name'=>$role]);
            }

            foreach ($permissions as $permission){
                try{
                    $dbPerm = Permission::findByName($permission);
                }catch(PermissionDoesNotExist $e) {
                    $dbPerm = Permission::create(['name'=>$permission]);
                }
                $dbRole->givePermissionTo($permission);
            }
        }
        
        $test_org_id = Organization::create([
            'org' => 'app',
            'domain' => 'test',
            'full_url' => 'www.app.test',
            'subdomain' => 'sub',
            'is_local_default_organization' => true,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ])->id;
        $test_org = Organization::find($test_org_id);

        $test_dept_id = Department::create([
            'key' => 'ict-admin',
            'long_name' => 'ICT Admin',
            'email' => 'ict-admin@app.com',
            'telephone' => '07085554141',
            'physical_location' => '2nd Floor, Room 20 - 28',
            'organization_id' => $test_org_id,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ])->id;
        $test_dept = Organization::find($test_dept_id);
                
        $test_admin_id = User::create([
            'email' => 'admin@app.com',
            'telephone' => '07063321200',
            'password' => Hash::make('password'),
            'first_name' => 'Admin',
            'last_name' => 'Admin',
            'organization_id' => $test_org_id,
            'last_loggedin_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ])->id;
        $test_user = User::find($test_admin_id);

       

        $ledger = Ledger::create([
            'name' => 'interventions',
        ]);

        Sanctum::actingAs(
            $test_user,
            ['*']
        );
        
        $this->beneficiaryData = [
            'organization_id' =>  Organization::first()->id,
            'email'=> $this->faker->unique()->email,
            'full_name'=> $this->faker->word,
            'short_name' => $this->faker->word,
            'type' => 'university',
            'official_email' => $this->faker->unique()->email,
            'official_website'=> "https://rmportal.ng",
            'official_phone'=> "08127345465",
            'address_street'=> $this->faker->word,
            'address_town'=> $this->faker->word,
            'address_state' => $this->faker->word,
            'geo_zone' => 'North Central'
            ];


            $this->beneficiaryMembershipData = [
                'organization_id' =>  Organization::first()->id,
                'user_id' =>  $test_admin_id,
                'status' => $this->faker->word,
                'role' => $this->faker->word, 
                'email' => $this->faker->unique()->email,
                
            ];

            $this->beneficiaryPaymentDetailData = [
                'organization_id' =>  Organization::first()->id,
                'bank_account_name' => $this->faker->word,
                'bank_account_number' => 1128577342, 
                'bank_name' => $this->faker->word,
                'bank_sort_code' => $this->faker->word,
            ];
            $this->interventionData = [
                'organization_id' => Organization::first()->id,
                'name' => $this->faker->word,
                'type' => $this->faker->word,
            ];
            $this->interventionBeneficiaryTypeData = [
                'organization_id' => Organization::first()->id,
                'name' => $this->faker->word,
                'type' => $this->faker->word,
            ];

            $this->allocatedFundData = [
                'organization_id' => Organization::first()->id,
                'allocation_type' => $this->faker->sentence,
                'allocation_code' => $this->faker->sentence,   
                'utilization_status' => $this->faker->sentence,
                'funding_description' => $this->faker->sentence,
                'file_number_refcode' => $this->faker->sentence,
                'ledger_id' => $ledger->id,
                'year' => "2021",
                'allocated_amount' => $this->faker->numberBetween(1000, 2000),
                'available_amount' => $this->faker->numberBetween(1000, 2000),
            ];

            $this->allocationSharingCriteriaData = [
                'organization_id' => Organization::first()->id,
                'name' => $this->faker->name,
                
            ];

            $this->allocationTemplateData = [
                'organization_id' => Organization::first()->id,
                'allocation_type' => $this->faker->sentence,
                'allocation_code' => $this->faker->sentence,
                'funding_description' => $this->faker->sentence,
                'file_number_refcode' => $this->faker->sentence,
                'year' => "2021",
                'allocated_amount' =>  $this->faker->numberBetween(1000, 2000),
                'available_amount' => $this->faker->numberBetween(1000, 2000),  
            ];

            $this->allocationUtilizationCriteriaData = [
                'organization_id' => Organization::first()->id,
                'name' => $this->faker->sentence,
            ];

            $this->communicationData = [
                'organization_id' => Organization::first()->id,
                'title' => $this->faker->word,
                'content' => $this->faker->sentence,
                'checker_user_id' => $test_admin_id,
                'recommender_user_id' => $test_admin_id,
                'approver_user_id' => $test_admin_id,
                'destination_label' => $this->faker->sentence,
                'creator_user_id' => $test_admin_id,
            ];

            $this->beneficiaryCommunicationData = [
                'organization_id' => Organization::first()->id,
            ];

            $communication = Communication::create($this->communicationData);
            $beneficiary = Beneficiary::create([
                'organization_id' =>  Organization::first()->id,
                'email'=> "abcd@example.com",
                'full_name'=> $this->faker->word,
                'short_name' => $this->faker->word,
                'type' => 'university',
                'official_email' => "abcd@example.com",
                'official_website'=> "https://rmportal.ng",
                'official_phone'=> "08127345465",
                'address_street'=> $this->faker->word,
                'address_town'=> $this->faker->word,
                'address_state' => $this->faker->word,
                'geo_zone' => 'North Central'
                ]);
            $intervention = Intervention::create($this->interventionData);
      
       
            $interventionBeneficiaryType = InterventionBeneficiaryType::create(array_merge($this->interventionBeneficiaryTypeData,['intervention_id' => $intervention->id]));
            $beneficiaryCommunication = BeneficiaryCommunication::create(array_merge($this->beneficiaryCommunicationData,['communication_id' => $communication->id,'beneficiary_id' => $beneficiary->id,'interven_benef_type_id' => $interventionBeneficiaryType->id]));

            $this->monitoring_request_data = [
                'organization_id' => Organization::first()->id,
                'title' => $this->faker->word,
                'beneficiary_id' => $beneficiary->id,
                'beneficiary_request_id' => $beneficiary->id,
                'monitoring_type' => $this->faker->word,
                'proposed_request_date' => $this->faker->date,
            ];

            $monitoring_request = MonitoringRequest::create($this->monitoring_request_data);
            
            $this->monitoring_schedule_data = [
                                        'organization_id' => Organization::first()->id,
                                        'title' => $this->faker->word,
                                        'monitoring_request_id' => $monitoring_request->id,
                                        'scheduler_user_id' => $test_user->id,
                                        'location' => $this->faker->word,
                                        'inspection_start_date' => $this->faker->date,
                                        'inspection_end_date' => $this->faker->date,
                                    ];

            $monitoring_schedule = MonitoringSchedule::create($this->monitoring_schedule_data);

            $this->monitoring_schedule_item_data = [
                'organization_id' => Organization::first()->id,
                'monitoring_schedule_id' => $monitoring_schedule->id,
                'monitoring_request_id' => $monitoring_request->id,
                'staff_user_id' => $test_user->id,
                'dta_amount' => $this->faker->numberBetween(0, 1000000),
                'air_ticket_amount' => $this->faker->numberBetween(0, 1000000),
                'airport_taxi_amount' => $this->faker->numberBetween(0, 1000000),
                'local_runs_amount' => $this->faker->numberBetween(0, 1000000),
                'fueling_amount' => $this->faker->numberBetween(0, 1000000),
                'special_local_runs_amount' => $this->faker->numberBetween(0, 1000000),
            ];

            $this->monitoring_request_communication_data = [
                'organization_id' => Organization::first()->id,
                'communication_id' => $communication->id,
                'beneficiary_id' => $beneficiary->id,
                'monitoring_req_id' => $monitoring_request->id
            ];

            $this->monitoring_request_report_data = [
                'organization_id' => Organization::first()->id,
                'monitoring_request_id' => $monitoring_request->id,
                'title' => $this->faker->word,
                'content' => $this->faker->sentence,
                'creator_user_id' => $test_user->id,
            ];

            $this->fa_audit_queries_data = [
                'organization_id' => Organization::first()->id,
                'subject_type_id' => $this->faker->word,
                'subject_type' => $this->faker->word,
                'destination' => $this->faker->sentence,
                'query_content' => $this->faker->sentence,
                'creator_user_id' => $test_user->id,
            ];

            $this->fa_audit_queries_data = [
                'organization_id' => Organization::first()->id,
                'subject_type_id' => $this->faker->word,
                'subject_type' => $this->faker->word,
                'destination' => $this->faker->sentence,
                'query_content' => $this->faker->sentence,
                'creator_user_id' => $test_user->id,
            ];

            $this->fa_payment_vouchers_data = [
                'organization_id' => Organization::first()->id,
                'subject_type_id' => $this->faker->word,
                'subject_type' => $this->faker->word,
                'voucher_code' => $this->faker->sentence,
                'file_ref_code' => $this->faker->word,
                'payee_name' => $this->faker->word,
                'payee_bank_name' => $this->faker->word,
                'payee_bank_sort_code' => $this->faker->word,
                'payee_bank_account_name' => $this->faker->word,
                'payee_bank_account_number' => $this->faker->word,
                'gross_amount' => $this->faker->numberBetween(0, 1000000),
                'epayment_schedule_number' => $this->faker->word,
                'vat_amount' => $this->faker->numberBetween(0, 1000000),
                'wht_amount' => $this->faker->numberBetween(0, 1000000),
                'net_amount' => $this->faker->numberBetween(0, 1000000),
                'payment_gateway_status' => $this->faker->word,
                'serial_no' => $this->faker->word,
                'head' => $this->faker->word,
                'sub_head' => $this->faker->word,
                'institution_code' => $this->faker->word,
                'details_of_payment' => $this->faker->word,
                'payment_description' => $this->faker->word,
                'creator_user_id' => $test_user->id,
            ];


    }


}