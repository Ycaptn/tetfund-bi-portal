<?php
namespace App\Listeners;

use App\Models\TSASNomination;
use App\Events\TSASNominationDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TSASNominationDeletedListener
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
     * @param  TSASNominationDeleted  $event
     * @return void
     */
    public function handle(TSASNominationDeleted $event)
    {
        //
    }
}
