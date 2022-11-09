<?php

namespace App\Listeners;

use App\Models\Beneficiary;
use App\Events\BeneficiaryCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class BeneficiaryCreatedListener
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
     * @param  BeneficiaryCreated  $event
     * @return void
     */
    public function handle(BeneficiaryCreated $event)
    {
        //
    }
}
