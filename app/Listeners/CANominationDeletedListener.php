<?php
namespace App\Listeners;

use App\Models\CANomination;
use App\Events\CANominationDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CANominationDeletedListener
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
     * @param  CANominationDeleted  $event
     * @return void
     */
    public function handle(CANominationDeleted $event)
    {
        //
    }
}
