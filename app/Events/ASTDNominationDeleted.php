<?php

namespace App\Events;

use App\Models\ASTDNomination;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ASTDNominationDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $aSTDNomination;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ASTDNomination $aSTDNomination)
    {
        $this->aSTDNomination = $aSTDNomination;
    }

}
