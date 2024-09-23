<?php

namespace App\Notifications;


use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class RefundedByDriver extends Notification {
    use Queueable;


    public $translatable;
    public $preferred_language;

    /**
     * Create a new notification instance.
     *
     * @param $translatable
     * @param string $preferred_language
     */
    public function __construct($translatable, $preferred_language) {
        $this->translatable = $translatable;
        $this->preferred_language = $preferred_language;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable) {
        return ['database','mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable) {
        return (new MailMessage)->from('no-reply@zoombus.net', 'Zoombus')->subject(
            \Lang::get('email_templates.ticket_refund_title', [], $this->preferred_language)
        )->view('email.app',
            [
                'locale' => ($this->preferred_language == 'ka') ? 'language_ge' : null,
                'title' =>
                    \Lang::get('email_templates.ticket_refund_title', [], $this->preferred_language),
                'text' =>
                    \Lang::get('email_templates.ticket_refund_text', $this->translatable[$this->preferred_language], $this->preferred_language)
            ]
        );
    }


    /**
     * Get the Nexmo SMS representation of the notification.
     *
     * @param mixed $notifiable
     * @return NexmoMessage
     */

    public function toNexmo($notifiable) {
        return (new NexmoMessage())
            ->content(Lang::get('notifications.ticket_refund', $this->translatable[$this->preferred_language], $this->preferred_language))->unicode();
    }


    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */

    public function toDatabase($notifiable) {
        foreach ($this->translatable as $k => $v) {
            $ins['text_' . $k] = \Lang::get('notifications.ticket_refund', $v, $k) ?? null;
        }
        $ins['type'] = 'refund';
        return $ins;
    }
}
