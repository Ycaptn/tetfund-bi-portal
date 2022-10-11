<?php

namespace App\Listeners;

use App\Models\SubmissionRequest;
use App\Models\SubmissionRequestDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SubmissionRequestDeletedListener
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
     * @param  SubmissionRequestDeleted  $event
     * @return void
     */
    public function handle(SubmissionRequestDeleted $event)
    {
        //
    }
}
