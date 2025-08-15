<?php

namespace App\Notifications;

use App\Broadcasting\FCM\FCMChannel;
use App\Broadcasting\FCM\FCMContent;
use App\Models\Order;
use App\Models\RepOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RepOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private const NOTIFICATION_ICONS = [
        'order_accepted' => 'check-circle'
    ];

    private const FCM_CLICK_ACTION = 'FLUTTER_NOTIFICATION_CLICK';

    private RepOrder $order;
    private string $type;
    private ?string $customMessage;

    /**
     * Create a new notification instance.
     */
    public function __construct(RepOrder $order, string $type, ?string $customMessage = null)
    {
        $this->order = $order;
        $this->type = $type;
        $this->customMessage = $customMessage;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [FCMChannel::class, 'database'];
    }

    /**
     * Get the FCM representation of the notification.
     *
     * @param object $notifiable
     * @return FCMContent
     */
    public function toFcm(object $notifiable): FCMContent
    {
        $tokens = $this->getFcmTokens($notifiable);

        $fcmData = [
            'order_id' => $this->order->id,
            'type' => $this->type,
            'click_action' => self::FCM_CLICK_ACTION
        ];

        return (new FCMContent)
            ->title($this->getTitle())
            ->body($this->getMessage())
            ->to($tokens)
            ->data(['json' => json_encode($fcmData)]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'type' => $this->type,
            'title' => $this->getTitle(),
            'description' => $this->getMessage(),
            'icon' => $this->getIcon(),
            'data' => [
                'status' => $this->order->status
            ]
        ];
    }

    /**
     * Get notification title.
     *
     * @return string
     */
    private function getTitle(): string
    {
        return $this->customMessage ?? __("notifications.rep_orders.{$this->type}.title");
    }

    /**
     * Get notification message.
     *
     * @return string
     */
    private function getMessage(): string
    {
        return __("notifications.rep_orders.{$this->type}.message");
    }

    /**
     * Get icon based on notification type.
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    private function getIcon(): string
    {
        if (!isset(self::NOTIFICATION_ICONS[$this->type])) {
            throw new \InvalidArgumentException("Invalid notification type: {$this->type}");
        }

        return self::NOTIFICATION_ICONS[$this->type];
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
