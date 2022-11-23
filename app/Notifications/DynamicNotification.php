<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DynamicNotification extends Notification
{
    use Queueable;
    private $fair;
    private $subject;
    private $title;
    
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($fair, $subject, $title)
    {
        $this->subject = $subject;
        $this->title = $title;
        
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
            ->subject($this->subject)
            ->view('notifications.dynamicNotification',
            [
                'title'=>$this->title
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