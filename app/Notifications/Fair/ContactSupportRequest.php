<?php

namespace App\Notifications\Fair;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactSupportRequest extends Notification
{
    use Queueable;


    protected $request;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        //
        $this->request = $request;
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
        return (new MailMessage)->view('notifications.fair.contactSupportRequest')
            ->with('data',$this->request);

        return (new MailMessage)
                    ->line('Notificación Contacto Soporte')
                    ->line(''.$this->request->name.' ha registrado una solicitud de soporte con el siguiente mensaje:')
                    ->line(''.$this->request->message)
                    ->line('Correo registrado : '.$this->request->email);
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
