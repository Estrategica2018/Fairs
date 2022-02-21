<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountRegistration extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $user;
    private $fair;
    private $origin;
    private $email;
    private $code;
    private $array_code = [];
    //public function __construct($user,$fair,$origin, $code)
    public function __construct($email, $code)
    {
        //

        while($code != 0){
            $this->array_code[] = $code % 10;
            $code = intval($code/10);
        }
        $this->email = $email;
        $this->code = $code;


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
        return (new MailMessage)->view('notifications.accountRegistration')
            ->with('array_code',$this->array_code);
        /*
        return (new MailMessage)
            //->line('Esta recibiendo este correo porque se ha finalizado con éxito el registro de su cuenta.')
            ->line('Esta recibiendo este correo porque esta registrando su correo para la Feria.')
            //->line('Deberá dar clic sobre este botón y usuar el siguiente código para activar su cuenta.')
            ->line('Deberá usuar el siguiente código para activar su cuenta.')
            ->line('Código:'.$this->code)
            //->action('Activar cuenta', $this->origin.'/user/activate/account/'.$this->user->id)
            ->line('Gracias por usar nuestra aplicación!');
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
