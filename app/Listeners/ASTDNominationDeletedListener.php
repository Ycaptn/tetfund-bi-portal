<?php
namespace App\Listeners;

use App\Models\ASTDNomination;
use App\Events\ASTDNominationDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ASTDNominationDeletedListener
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
     * @param  ASTDNominationDeleted  $event
     * @return void
     */
    public function handle(ASTDNominationDeleted $event)
    {
        //
    }
}
