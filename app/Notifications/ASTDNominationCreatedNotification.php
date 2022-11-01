<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\ASTDNomination;

class ASTDNominationCreatedNotification extends Notification
{

    use Queueable;


    public $aSTDNomination;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ASTDNomination $aSTDNomination)
    {
        $this->aSTDNomination = $aSTDNomination;
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
        return (new MailMessage)->subject('ASTDNomination created successfully')
                                ->markdown(
                                    'mail.aSTDNominations.created',
                                    ['aSTDNomination' => $this->aSTDNomination]
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
