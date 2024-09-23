<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class DriversLicense extends Notification
{
    use Queueable;

    private $translatable;
    private $preferred_language;
    private $supportTicketId;
    private $supportTicketMessageId;

    /**
     * Create a new notification instance.
     *
     * @param array $translatable
     * @param string $preferred_language
     * @param null $supportTicketId
     * @param null $supportTicketMessageId
     */
    public function __construct(array $translatable, $preferred_language = 'en', $supportTicketId = null, $supportTicketMessageId = null)
    {
        $this->translatable = $translatable;
        $this->preferred_language = $preferred_language;
        $this->supportTicketId = $supportTicketId;
        $this->supportTicketMessageId = $supportTicketMessageId;
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
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)->from('no-reply@zoombus.net', 'Zoombus')->subject(
            \Lang::get('email_templates.drivers_license_title', $this->translatable[$this->preferred_language], $this->preferred_language)
        )->view('email.app',
            [
                'locale' => ($this->preferred_language == 'ka') ? 'language_ge' : null,
                'title' =>
                    \Lang::get('email_templates.drivers_license_title', $this->translatable[$this->preferred_language], $this->preferred_language),
                'text' =>
                    \Lang::get('email_templates.drivers_license_text', $this->translatable[$this->preferred_language], $this->preferred_language),
                'button_url' =>
                    (empty($this->supportTicketId) || empty($this->supportTicketMessageId)) ?
                    route('drivers_license') :
                    route('drivers_license', ['ticket' => $this->supportTicketId, 'message' => $this->supportTicketMessageId]),
                'button_anchor' => Lang::get('email_templates.drivers_license_button', [], $this->preferred_language)
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
        foreach($this->translatable as $k => $v) {
            $ins['text_'.$k] = \Lang::get('notifications.license', $v, $k) ?? null;
        }
        $ins['url'] =
            (empty($this->supportTicketId) || empty($this->supportTicketMessageId)) ?
            route('drivers_license') :
            route('drivers_license', ['ticket' => $this->supportTicketId, 'message' => $this->supportTicketMessageId]);
        $ins['type'] = 'license';
        return $ins;
    }
}
