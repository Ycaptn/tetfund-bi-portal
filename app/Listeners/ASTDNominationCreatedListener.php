<?php

namespace App\Listeners;

use App\Models\ASTDNomination;
use App\Models\ASTDNominationCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ASTDNominationCreatedListener
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
