<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class RemovedFromCart extends Notification
{
    use Queueable;


    public $translatable;

    /**
     * Create a new notification instance.
     *
     * @param array $translatable
     */

    public function __construct(array $translatable)
    {
        $this->translatable = $translatable;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        foreach($this->translatable as $k => $v) {
            $ins['text_'.$k] = \Lang::get('notifications.cart', $v, $k) ?? null;
        }
        $ins['url'] = route('cart');
        $ins['type'] = 'cart';
        return $ins;
    }
}
