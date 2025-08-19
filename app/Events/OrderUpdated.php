<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $userId;

    public function __construct($order, $userId)
    {
        $this->order = $order;
        $this->userId = $userId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->userId);
    }

    public function broadcastAs()
    {
        return 'order-updated';
    }

    public function broadcastWith()
    {
        return [
            'order_id' => $this->order->id ?? $this->order['id'],
            'status' => $this->order->status ?? $this->order['status'],
            'updated_at' => $this->order->updated_at ?? $this->order['updated_at'],
        ];
    }
}
