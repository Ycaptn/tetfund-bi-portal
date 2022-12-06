<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\TPNomination;

class TPNominationCreatedNotification extends Notification
{

    use Queueable;


    public $tPNomination;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TPNomination $tPNomination)
    {
        $this->tPNomination = $tPNomination;
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
        return (new MailMessage)->subject('TPNomination created successfully')
                                ->markdown(
                                    'mail.tPNominations.created',
                                    ['tPNomination' => $this->tPNomination]
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
