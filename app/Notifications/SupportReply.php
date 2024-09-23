<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class SupportReply extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public $translatable;
    public $url;
    public $preferred_language;


    public function __construct($translatable, $preferred_language, $url = null)
    {
        $this->translatable = $translatable;
        $this->url = $url;
        $this->preferred_language = $preferred_language;
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
        return (new MailMessage)->from('no-reply@zoombus.net', 'Zoombus')->subject(
            Lang::get('email_templates.support_reply_title', $this->translatable[$this->preferred_language], $this->preferred_language)
        )->view('email.app',
            [
                'locale' => ($this->preferred_language == 'ka') ? 'language_ge' : null,
                'title' =>
                    Lang::get('email_templates.support_reply_title', $this->translatable[$this->preferred_language], $this->preferred_language),
                'text' =>
                    Lang::get('email_templates.support_reply_text', $this->translatable[$this->preferred_language], $this->preferred_language),
                'button_url' => $this->url,
                'button_anchor' =>
                    Lang::get('email_templates.support_reply_anchor', [], $this->preferred_language)
            ]
        );
    }


}
