<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\CANomination;

class CANominationUpdatedNotification extends Notification
{

    use Queueable;


    public $cANomination;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(CANomination $cANomination)
    {
        $this->cANomination = $cANomination;
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
        return (new MailMessage)->subject('CANomination updated successfully')
                                ->markdown(
                                    'mail.cANominations.updated',
                                    ['cANomination' => $this->cANomination]
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
