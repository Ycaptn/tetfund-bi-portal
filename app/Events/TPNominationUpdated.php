<?php

namespace App\Events;

use App\Models\ASTDNomination as TPNomination;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TPNominationUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tPNomination;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TPNomination $tPNomination)
    {
        $this->tPNomination = $tPNomination;
    }

}
