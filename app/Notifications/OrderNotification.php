<?php

namespace App\Notifications;

use App\Broadcasting\FCM\FCMChannel;
use App\Broadcasting\FCM\FCMContent;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private const NOTIFICATION_ICONS = [
        'order_created' => 'clock',
        'order_accepted' => 'check-circle',
        'order_rejected' => 'times-circle',
        'order_processing' => 'clock',
        'order_shipped' => 'truck',
        'order_delivered' => 'box',
        'order_completed' => 'box',
        'order_cancelled' => 'ban'
    ];

    private const FCM_CLICK_ACTION = 'FLUTTER_NOTIFICATION_CLICK';

    private Order $order;
    private string $type;
    private ?string $customMessage;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, string $type, ?string $customMessage = null)
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
        \Log::info('FCM Tokens:', $tokens);

        $fcmData = [
            'order_id' => $this->order->id,
            'type' => $this->type,
            'click_action' => self::FCM_CLICK_ACTION
        ];

        \Log::info('FCM Data:', $fcmData);

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
                'order_number' => $this->order->order_number,
                'status' => $this->order->status,
                'amount' => $this->order->total_amount
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
        return $this->customMessage ?? __("notifications.orders.{$this->type}.title");
    }

    /**
     * Get notification message.
     *
     * @return string
     */
    private function getMessage(): string
    {
        return __("notifications.orders.{$this->type}.message", [
            'order_number' => encodeString($this->order->id),
            'amount' => number_format($this->order->total_amount, 2)
        ]);
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
