<?php

namespace App\Listeners;

use App\Models\Beneficiary;
use App\Events\BeneficiaryUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BeneficiaryUpdatedListener
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
     * @param  BeneficiaryUpdated  $event
     * @return void
     */
    public function handle(BeneficiaryUpdated $event)
    {
        //
    }
}
