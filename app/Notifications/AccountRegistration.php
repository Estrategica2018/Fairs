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
    public $array_code = [];
    public function __construct($email, $code, $fairName)
    {
        while($code != 0){
            $this->array_code[] = $code % 10;
            $code = intval($code/10);
        }
        $this->email = $email;
		$this->origin = 'https://'.$fairName.'.e-logic.com.co/app-dialog/confirmAccount/'.$email;
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
        return (new MailMessage)
            ->from('admin@e-logic.com.co', 'Feria virtual e-logic')
            ->subject('Notificación Registro Feria')
            ->view('notifications.accountRegistration',[
					'array_code'=>$this->array_code,
					'origin'=>$this->origin]);
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
