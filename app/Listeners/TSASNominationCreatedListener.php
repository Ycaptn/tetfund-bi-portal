<?php

namespace App\Listeners;

use App\Models\TSASNomination;
use App\Events\TSASNominationCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TSASNominationCreatedListener
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
     * @param  TSASNominationCreated  $event
     * @return void
     */
    public function handle(TSASNominationCreated $event)
    {
        //
    }
}
