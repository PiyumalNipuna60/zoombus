<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Support\Facades\Lang;

class RouteReminder extends Notification implements ShouldQueue
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
        return ['database','mail'];
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
            \Lang::get('email_templates.route_reminder_title', [], $this->preferred_language)
        )->view('email.app',
                        [
                            'locale' => ($this->preferred_language == 'ka') ? 'language_ge' : null,
                            'title' =>
                                \Lang::get('email_templates.route_reminder_title', [], $this->preferred_language),
                            'text' =>
                                \Lang::get('email_templates.route_reminder_text', $this->translatable[$this->preferred_language], $this->preferred_language),
                            'button_url' => $this->url,
                            'button_anchor' =>
                                \Lang::get('email_templates.route_button_anchor', [], $this->preferred_language)
                        ]
                    );
    }



    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        foreach($this->translatable as $k=>$v) {
            $ins['text_'.$k] = Lang::get('notifications.reminder', $v, $k) ?? null;
        }
        $ins['url'] = $this->url;
        $ins['type'] = 'reminder';
        return $ins;
    }
}
