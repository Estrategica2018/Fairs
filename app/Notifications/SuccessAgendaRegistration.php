<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuccessAgendaRegistration extends Notification
{
    use Queueable;
    private $fair;
    private $email;
    private $btnLink;
    private $agenda;
    private $dayFormat;
    private $durationStr;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($fair,$email, $agenda, $dayFormat, $durationStr)
    {
        //
        $this->btnLink = 'https://'.$fair->name.'.e-logic.com.co/website/agenda/' . $agenda->id;
        $this->agenda = $agenda;
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
            ->subject('Notificación Registro a Evento Exitoso')
            ->view('notifications.successfulAgendaRegistration',
            [
                'btnLink'=>$this->btnLink,
                'agenda'=>$this->agenda,
                'dayFormat'=>$this->dayFormat,
                'durationStr'=>$this->durationStr
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
