<?php
namespace App\Listeners;

use App\Models\CANomination;
use App\Events\CANominationUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CANominationUpdatedListener
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
     * @param  CANominationUpdated  $event
     * @return void
     */
    public function handle(CANominationUpdated $event)
    {
        //
    }
}
