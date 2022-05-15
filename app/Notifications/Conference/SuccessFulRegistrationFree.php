<?php

namespace App\Notifications\Conference;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuccessFulRegistrationFree extends Notification
{
    use Queueable;

    protected $agenda;
    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($agenda,$user)
    {
        //
        $this->agenda = $agenda;
        $this->user = $user;
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
        return (new MailMessage)
            ->from('admin@e-logic.com.co', 'Feria virtual e-logic')
            ->subject('Notificación Registro Agenda - Gratuita')
            ->view('notifications.conference.successfulRegistrationFree',
                [
                    'agenda' => $this->agenda,
                    'user' => $this->agenda
                ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
