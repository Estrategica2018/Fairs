<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuccessfulPaymentMechant extends Notification
{
    use Queueable;

    private $data;
    private $merchant;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( $data , $merchant)
    {
        //
        $this->data = $data;
        $this->merchant = $merchant;
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
            ->line('Se ha registrado un pago para su comercio '. $this->merchant['name'] .'  con estado Exitoso.')
            ->line('A continuación pude ver el detalle de la compra.')
            ->line('Metodo :'.$this->data['payment_method_type'])
            ->line('Costo total :'.$this->merchant['total'])
            ->line('Descripción :'.$this->data['payment_method']['payment_description'])
            ->line('Estado :'.$this->data['status'])
            ->line('Gracias por usar nuestra aplicación');
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
