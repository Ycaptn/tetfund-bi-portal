<?php

namespace App\Listeners;

use App\Models\SubmissionRequest;
use App\Models\SubmissionRequestCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SubmissionRequestCreatedListener
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
     * @param  SubmissionRequestCreated  $event
     * @return void
     */
    public function handle(SubmissionRequestCreated $event)
    {
        //
    }
}
