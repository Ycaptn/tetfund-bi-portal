<?php

namespace App\Listeners;

use App\Models\CANomination;
use App\Events\CANominationCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CANominationCreatedListener
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
     * @param  CANominationCreated  $event
     * @return void
     */
    public function handle(CANominationCreated $event)
    {
        //
    }
}
