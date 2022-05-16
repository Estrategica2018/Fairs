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
    private $btnLink;
    private $email;
    private $code;
    private $iconUrl;
    public $backgroundColor;
    public $cardColor;
    public $array_code = [];
    public function __construct($email, $code, $fair)
    {
        while($code != 0){
            $this->array_code[] = $code % 10;
            $code = intval($code/10);
        }
        $this->email = $email;
        $this->btnLink = 'https://'.$fair->name.'.e-logic.com.co/app-dialog/confirmAccount/'.$email;
		$this->code = $code;
		
		$this->cardColor = isset($fair->social_media->cardColor) ? $fair->social_media->cardColor : '#f4f4f4';
		$this->backgroundColor = isset($fair->social_media->backgroundColor) ? $fair->social_media->backgroundColor : '#f4f4f4';
		$this->iconUrl = $fair->social_media->icon;
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
					'iconUrl'=>$this->iconUrl,
					'backgroundColor'=> $this->backgroundColor,
					'cardColor'=> $this->cardColor,
					'btnLink'=>$this->btnLink]);
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
