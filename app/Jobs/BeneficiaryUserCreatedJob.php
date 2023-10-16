<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\BeneficiaryUserCreatedNotification;
use Notification;

class BeneficiaryUserCreatedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $user;
    public $input;
    public function __construct($user, $input)
    {
        $this->user = $user;
        $this->input = $input;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // catch email notification error
        try {
            //code...
            Notification::send($this->user, new BeneficiaryUserCreatedNotification($this->user, $this->input));
        } catch (\Throwable $th) {
            //throw $th;
            \Log::error($th->getMessage());
        }

       
    }
}
