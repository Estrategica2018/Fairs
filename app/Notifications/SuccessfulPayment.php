<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SuccessfulPayment extends Notification
{
    use Queueable;
    private $transaction;
    private $shoppingCart;
    private $totalPrice;
    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public function __construct($transaction,$shoppingCart,$totalPrice)
    {
        //
        $this->transaction = $transaction;
        $this->shoppingCart = $shoppingCart;
        $this->totalPrice = $totalPrice;
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
            ->view('notifications.successfulPayment',
                [ 'transaction' => $this->transaction,
                    'shoppingCart' => $this->shoppingCart,
                    'totalPrice' => $this->totalPrice
                ]
            );
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
