<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class SupportReplyNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public $translatable;
    public $url;
    public $user_id;
    public $ticket_id;


    public function __construct($translatable, $user_id, $url = null, $ticket_id = null)
    {
        $this->translatable = $translatable;
        $this->user_id = $user_id;
        $this->url = $url;
        $this->ticket_id = $ticket_id;
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


    public function toDatabase($notifiable)
    {
        foreach($this->translatable as $k=>$v) {
            $ins['text_'.$k] = Lang::get('notifications.support', $v, $k) ?? null;
        }
        $ins['url'] = $this->url;
        $ins['user_id'] = $this->user_id;
        $ins['ticket_id'] = $this->ticket_id;
        $ins['type'] = 'support';
        return $ins;
    }

}
