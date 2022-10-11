<?php

namespace App\Listeners;

use App\Models\Beneficiary;
use App\Models\BeneficiaryDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BeneficiaryDeletedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  BeneficiaryDeleted  $event
     * @return void
     */
    public function handle(BeneficiaryDeleted $event)
    {
        //
    }
}
