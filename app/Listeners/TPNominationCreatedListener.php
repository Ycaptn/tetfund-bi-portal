<?php

namespace App\Listeners;

use App\Models\ASTDNomination as TPNomination;
use App\Events\ASTDNominationCreated;
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
     * @param  ASTDNominationCreated  $event
     * @return void
     */
    public function handle(ASTDNominationCreated $event)
    {
        //
    }
}
