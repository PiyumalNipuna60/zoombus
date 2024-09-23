<?php

namespace App\Notifications;


use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class TicketOrder extends Notification{
    use Queueable;


    public $translatable;
    public $url;
    public $preferred_language;
    public $phone;

    public function __construct($translatable, $preferred_language, $url = null) {
        $this->translatable = $translatable;
        $this->url = $url;
        $this->preferred_language = $preferred_language;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable) {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */

    public function toMail($notifiable) {
        return (new MailMessage)->from('sale@zoombus.net', 'Zoombus')->subject(
            Lang::get('email_templates.ticket_purchase_title', [], $this->preferred_language)
        )->view('email.app',
            [
                'locale' => ($this->preferred_language == 'ka') ? 'language_ge' : null,
                'title' =>
                    Lang::get('email_templates.ticket_purchase_title', [], $this->preferred_language),
                'text' =>
                    Lang::get('email_templates.ticket_purchase_text', $this->translatable, $this->preferred_language),
                'button_url' => $this->url,
                'button_anchor' =>
                    Lang::get('email_templates.ticket_purchase_anchor', [], $this->preferred_language)
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
            ->content(Lang::get('notifications.ticket_purchase', ['url' => $this->url], $this->preferred_language))->unicode();
    }
}
