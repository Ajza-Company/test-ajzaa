<?php

namespace App\Broadcasting\Sms;

use Exception;

class SMSChannel
{
    public function __construct(private SMSService $smsService)
    {
    }

    /**
     * @throws Exception
     */
    public function send($notifiable, $notification): bool
    {
        if (!method_exists($notification, 'toSMS')) {
            throw new Exception('Notification class must implement toSMS method');
        }

        $message = $notification->toSMS($notifiable);

        if (!is_array($message)) {
            throw new Exception('toSMS must return an array');
        }

        return $this->smsService->sendMessage(
            $message['to'] ?? $notifiable->routeNotificationFor('sms'),
            $message['content']
        );
    }
}
