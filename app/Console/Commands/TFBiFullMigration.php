<?php

namespace App\Console\Commands;

use Session;
use Validator;
use Carbon\Carbon;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

use Hasob\FoundationCore\Models\Organization;
use Hasob\FoundationCore\Managers\OrganizationManager;

class TFBiFullMigration extends Command
{
    
    protected $signature = 'tetfund:bi-full-migration';
    protected $description = 'Migrates all relevant data from TETFund_Iterum_Portal database to TETFund_BI_Portal';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        echo "Running Full Migration from TETFund_Iterum_Portal Database to TETFund_Bi_Portal Database \n \n \n";

        \Artisan::call('cache:clear');
        \Artisan::call('config:cache');
        \Artisan::call('clear-compiled');
        \Artisan::call('optimize');

        \Artisan::call('tetfund:bi-submitted-beneficiary-requests-migration');
        \Artisan::call('tetfund:bi-un-submitted-beneficiary-requests-migration');
        \Artisan::call('tetfund:bi-submitted-monitoring-requests-migration');
        \Artisan::call('tetfund:bi-un-submitted-monitoring-requests-migration');
        \Artisan::call('tetfund:bi-un-submitted-monitoring-requests-migration');
        \Artisan::call('tetfund:bi-delete-all-attachments-not-uploaded-by-bi-staff');


        echo " \n";
        echo "Done Migration \n";

        return 1;
    }
}
