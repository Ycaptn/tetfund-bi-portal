<?php

namespace App\Listeners;

use App\Models\SubmissionRequest;
use App\Events\SubmissionRequestUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SubmissionRequestUpdatedListener
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
     * @param  SubmissionRequestUpdated  $event
     * @return void
     */
    public function handle(SubmissionRequestUpdated $event)
    {
        //
    }
}
