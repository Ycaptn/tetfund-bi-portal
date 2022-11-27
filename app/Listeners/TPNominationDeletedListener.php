<?php
namespace App\Listeners;

use App\Models\ASTDNomination as TPNomination;
use App\Events\TPNominationDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TPNominationDeletedListener
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
     * @param  TPNominationDeleted  $event
     * @return void
     */
    public function handle(TPNominationDeleted $event)
    {
        //
    }
}
