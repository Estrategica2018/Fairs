<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UnsuccessfulPayment extends Notification
{
    use Queueable;

    private $data;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( $data )
    {
        //
        $this->data = $data;

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
        return (new MailMessage)->view('notifications.unsuccessfulPayment')
            ->with('transaction', $this->transaction);

        /*
        return (new MailMessage)
                    ->line('Se ha registrado un pago con esta Declinado.')
                    ->line('A continuación pude ver el detalle de la compra.')
                    ->line('Metodo :'.$this->data['payment_method_type'])
                    ->line('Costo total :'.$this->data['amount_in_cents'].' '.$this->data['currency'])
                    ->line('Descripción :'.$this->data['payment_method']['payment_description'])
                    ->line('Estado :'.$this->data['status'])
                    ->line('Gracias por usar nuestra aplicación');
        */
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
