<?php


namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\SubmissionRequest;

class SubmissionRequestDeletedNotification extends Notification
{

    use Queueable;


    public $submissionRequest;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(SubmissionRequest $submissionRequest)
    {
        $this->submissionRequest = $submissionRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)->subject('SubmissionRequest deleted successfully')
                                ->markdown(
                                    'mail.submissionRequests.deleted',
                                    ['submissionRequest' => $this->submissionRequest]
                                );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [];
    }

}
