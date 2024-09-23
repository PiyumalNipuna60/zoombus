<?php

namespace App\Broadcasting;

use App\Notifications\Messages\AwsSnsMessage;
use Illuminate\Notifications\Notification;
use Aws\Sns\SnsClient;
use Aws\Exception\AwsException;
use Illuminate\Support\Facades\Log;

class AwsSnsChannel
{

    public $snsClient;

    /**
     * Create a new channel instance.
     *
     * @param SnsClient $snsClient
     */
    public function __construct(SnsClient $snsClient)
    {
        $this->snsClient = $snsClient;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     * @return \Aws\Result|void
     */
    public function send($notifiable, Notification $notification)
    {
        if (!$to = $notifiable->routeNotificationFor('sns', $notification)) {
            return;
        }

        $messageToSend = $notification->toAmazonSNS($notifiable);


        if (is_string($messageToSend->content)) {
            try {
                return $this->snsClient->publish([
                    'Message' => $messageToSend->content,
                    'PhoneNumber' => $to,
                    'MessageAttributes' => [
                        'AWS.SNS.SMS.SenderID' => [
                            'DataType' => 'String',
                            'StringValue' => 'Zoombus'
                        ]
                    ],
                ]);

            } catch (AwsException $e) {
                // output error message if fails
                Log::critical($e->getMessage());
            }
        }


    }
}
