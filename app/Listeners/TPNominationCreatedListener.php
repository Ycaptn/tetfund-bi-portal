<?php

namespace App\Listeners;

use App\Models\TPNomination;
use App\Events\TPNominationCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TPNominationCreatedListener
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
     * @param  TPNominationCreated  $event
     * @return void
     */
    public function handle(TPNominationCreated $event)
    {
        //
    }
}
