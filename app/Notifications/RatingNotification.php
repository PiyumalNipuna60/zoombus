<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RatingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $translatable;
    public $url;
    public $preferred_language;

    /**
     * Create a new notification instance.
     *
     * @param $translatable
     * @param $preferred_language
     * @param null $url
     */

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
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return MailMessage
     */
    public function toMail()
    {
        return (new MailMessage)->from('no-reply@zoombus.net', 'Zoombus')->subject(
            \Lang::get('email_templates.rating_title', [], $this->preferred_language)
        )->view('email.app',
            [
                'locale' => ($this->preferred_language == 'ka') ? 'language_ge' : null,
                'title' =>
                    \Lang::get('email_templates.rating_title', [], $this->preferred_language),
                'text' =>
                    \Lang::get('email_templates.rating_text', $this->translatable[$this->preferred_language], $this->preferred_language),
                'button_url' => $this->url,
                'button_anchor' =>
                    \Lang::get('email_templates.rating_anchor', [], $this->preferred_language)
            ]
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toDatabase()
    {
        foreach($this->translatable as $k=>$v) {
            $ins['text_'.$k] = \Lang::get('notifications.rating', $v, $k) ?? null;
        }
        $ins['url'] = $this->url;
        $ins['type'] = 'reminder';
        return $ins;
    }
}
