<?php

namespace Database\Seeders;

use Hash;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Hasob\FoundationCore\Models\User;
use Hasob\FoundationCore\Models\Department;
use Hasob\FoundationCore\Models\Organization;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        //Create default organization
        $default_org_id = null;
        if (DB::table('fc_organizations')->count() == 0){
            $default_org_id = Organization::create([
                'org' => 'app',
                'domain' => 'test',
                'full_url' => 'www.app.test',
                'subdomain' => 'sub',
                'is_local_default_organization' => true,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ])->id;
        }else{
            $default_org_id = DB::table('fc_organizations')->where('is_local_default_organization', true)->select('id')->first()->id;
        }
        
        //seeder for ict department
        if (DB::table('fc_departments')->where('key', 'ict')->count() == 0){
         $ict_department = Department::create([
                'key' => 'ict',
                'long_name' => 'Information and Communication Technology',
                'email' => 'ict@app.com',
                'telephone' => '09023142658',
                'physical_location' => '4th Floor, Room 8A',
                'organization_id' => $default_org_id,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ])->id;
         } else {
            $ict_department = DB::table('fc_departments')->where('key', 'ict')->first()->id;
         }

        //seeder for PI department
        if (DB::table('fc_departments')->where('key', 'pi')->count() == 0){
         $pi_department = Department::create([
                'key' => 'pi',
                'long_name' => 'Physical Infrastructure',
                'email' => 'pi@app.com',
                'telephone' => '09085624132',
                'physical_location' => '5th Floor, Room 9A',
                'organization_id' => $default_org_id,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ])->id;
         } else {
            $pi_department = DB::table('fc_departments')->where('key', 'pi')->first()->id;
         }

        //seeder for ESS department
        if (DB::table('fc_departments')->where('key', 'ess')->count() == 0){
            $ess_department = Department::create([
                   'key' => 'ess',
                   'long_name' => 'Education Support Services',
                   'email' => 'ess@app.com',
                   'telephone' => '08038737334',
                   'physical_location' => '6th Floor, Room 9A',
                   'organization_id' => $default_org_id,
                   'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                   'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
               ])->id;
            } else {
               $ess_department = DB::table('fc_departments')->where('key', 'ess')->first()->id;
            }

        //users for ict department
        if (DB::table('fc_users')->where('email', 'directorict@app.com')->count() == 0){
            User::create([
                'email' => 'directorict@app.com',
                'telephone' => '08041224568',
                'password' => Hash::make('password'),
                'first_name' => 'Director',
                'last_name' => 'ICT',
                'organization_id' => $default_org_id,
                'department_id'=>$ict_department,
                'last_loggedin_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        //users for pi department
        if (DB::table('fc_users')->where('email', 'directorpi@app.com')->count() == 0){
            User::create([
                'email' => 'directorpi@app.com',
                'telephone' => '08086542214',
                'password' => Hash::make('password'),
                'first_name' => 'Director',
                'last_name' => 'PI',
                'organization_id' => $default_org_id,
                'department_id'=>$pi_department,
                'last_loggedin_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

         //users for ess department
         if (DB::table('fc_users')->where('email', 'directoress@app.com')->count() == 0){
            User::create([
                'email' => 'directoress@app.com',
                'telephone' => '08059048344',
                'password' => Hash::make('password'),
                'first_name' => 'Director',
                'last_name' => 'ESS',
                'organization_id' => $default_org_id,
                'department_id'=> $ess_department,
                'last_loggedin_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
}
