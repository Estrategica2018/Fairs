<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetRequest extends Notification
{
    use Queueable;

    protected $token;
    protected $origin;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token,$origin)
    {
        $this->token = $token;
        $this->origin = $origin;
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
        $url = $this->origin.'/#/recoverPassword/'.$this->token;
        return (new MailMessage)
            ->line('Estas recibiendo este correo porque nosotros hemos recibido una solicitud para restablecer la contraseña de tu cuenta.')
            ->action('Restablecer Contraseña', url($url))
            ->line('Si no solicitaste un restablecimiento de contraseña, no es necesario realizar ninguna otra acción.');
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
