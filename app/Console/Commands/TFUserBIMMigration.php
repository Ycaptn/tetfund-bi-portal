<?php

namespace App\Console\Commands;

use Carbon\Carbon;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;



use Hasob\FoundationCore\Models\Organization;
use Hasob\FoundationCore\Managers\OrganizationManager;
use Hasob\FoundationCore\Models\User;
use App\Jobs\SyncUsersToBIMSJob;


class TFUserBIMMigration extends Command
{
    
    protected $signature = 'tetfund:user-to-bims-migration';
    protected $description = 'Migrates database users to bims';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        
        $users = User::where("email","<>","admin@app.com")->get();

        foreach ($users as $key => $user) {
            # code...

            SyncUsersToBIMSJob::dispatch($user);
        }

       
    }

}