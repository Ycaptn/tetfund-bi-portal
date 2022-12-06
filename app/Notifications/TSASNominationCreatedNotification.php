<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\TSASNomination;

class TSASNominationCreatedNotification extends Notification
{

    use Queueable;


    public $tSASNomination;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TSASNomination $tSASNomination)
    {
        $this->tSASNomination = $tSASNomination;
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
        return (new MailMessage)->subject('TSASNomination created successfully')
                                ->markdown(
                                    'mail.tSASNominations.created',
                                    ['tSASNomination' => $this->tSASNomination]
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
