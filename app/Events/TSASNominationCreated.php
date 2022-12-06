<?php

namespace App\Events;

use App\Models\TSASNomination;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TSASNominationCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tSASNomination;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TSASNomination $tSASNomination)
    {
        $this->tSASNomination = $tSASNomination;
    }

}
