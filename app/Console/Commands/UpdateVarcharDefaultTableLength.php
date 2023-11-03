<?php

namespace App\Console\Commands;

use Carbon\Carbon;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;



use Hasob\FoundationCore\Models\Organization;
use Hasob\FoundationCore\Managers\OrganizationManager;



class UpdateVarcharDefaultTableLength extends Command
{
    
    protected $signature = 'tetfund:update-table-default-string-length';
    protected $description = 'Migrates over-ridden beneficiary from TETFund_Iterum_Portal database to TETFund BI-Portal';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        $host = request()->getHost();
        $manager = new OrganizationManager();
        $organization = $manager->loadTenant($host);
        
        // Update the character length for varchar data type in database;

        $tables = DB::select('SHOW TABLES');
        $schema = DB::getSchemaBuilder();
        $database_name = "Tables_in_".config('database.connections.mysql.database');
     
        foreach ($tables as $key => $table) {
           
            $columns = DB::select("describe ".$table->$database_name );
            $table_name = $table->$database_name;
            echo "checking table $table_name \n";

            foreach ($columns as $key => $column) {
                # code...
                $type = $column->Type; 
                $field_name = $column->Field;
                if($type == "varchar(125)"){
                    echo "updating column $field_name to 191 string length from $table_name table \n";
                  
                    DB::statement("ALTER TABLE $table_name CHANGE `$field_name` `$field_name` varchar(191)");
                 
                }
              
            }

         
        }

        return 0;
       
    }

}