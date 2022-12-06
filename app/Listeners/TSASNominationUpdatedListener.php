<?php
namespace App\Listeners;

use App\Models\TSASNomination;
use App\Events\TSASNominationUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TSASNominationUpdatedListener
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
     * @param  TSASNominationUpdated  $event
     * @return void
     */
    public function handle(TSASNominationUpdated $event)
    {
        //
    }
}
