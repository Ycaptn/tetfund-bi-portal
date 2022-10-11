<?php

namespace App\Events;

use App\Models\SubmissionRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubmissionRequestCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $submissionRequest;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(SubmissionRequest $submissionRequest)
    {
        $this->submissionRequest = $submissionRequest;
    }

}
