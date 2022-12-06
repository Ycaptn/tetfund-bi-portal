<?php
namespace App\Listeners;

use App\Models\TPNomination;
use App\Events\TPNominationUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TPNominationUpdatedListener
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
     * @param  TPNominationUpdated  $event
     * @return void
     */
    public function handle(TPNominationUpdated $event)
    {
        //
    }
}
