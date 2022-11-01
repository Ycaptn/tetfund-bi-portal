<?php
namespace App\Listeners;

use App\Models\ASTDNomination;
use App\Models\ASTDNominationUpdated;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ASTDNominationUpdatedListener
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
     * @param  ASTDNominationUpdated  $event
     * @return void
     */
    public function handle(ASTDNominationUpdated $event)
    {
        //
    }
}
