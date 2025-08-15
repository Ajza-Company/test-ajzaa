<?php

namespace App\Notifications;

use App\Broadcasting\FCM\FCMChannel;
use App\Broadcasting\FCM\FCMContent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendDynamicNotification extends Notification implements ShouldQueue
{
    use Queueable;
    private $message;
    private $title;
    private $dataObject;

    /**
     * Create a new notification instance.
     *
     * @param String $title
     * @param String $message
     * @param array $tokens
     * @param String|null $image
     * @param array $dataObject
     */
    public function __construct(String $title, String $message, array $dataObject = [])
    {
        $this->title = $title;
        $this->message = $message;
        $this->dataObject = $dataObject;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [FCMChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toFcm(object $notifiable): FCMContent
    {
        $tokens = $this->getFcmTokens($notifiable);

        $this->dataObject = json_encode(array_merge($this->dataObject, [
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
        ]));

        return (new FCMContent)
            ->title($this->title)
            ->body($this->message)
            ->to($tokens)
            ->data(['json' => $this->dataObject]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    /**
     * Get FCM tokens for the notifiable entity.
     *
     * @param object $notifiable
     * @return array
     */
    private function getFcmTokens(object $notifiable): array
    {
        return $notifiable->userFcmTokens->pluck('token')->toArray() ?? [];
    }
}
