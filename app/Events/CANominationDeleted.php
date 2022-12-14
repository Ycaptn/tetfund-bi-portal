<?php

namespace App\Events;

use App\Models\CANomination;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CANominationDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $cANomination;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(CANomination $cANomination)
    {
        $this->cANomination = $cANomination;
    }

}
