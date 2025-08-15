<?php

namespace App\Events\v1\Supplier;

use App\Http\Resources\v1\Frontend\Order\F_ShortOrderResource;
use App\Models\Order;
use App\Models\RepOrder;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class S_TrackRepOrderEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(private RepOrder $order)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('rep-order.' . encodeString($this->order->id)),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        $trackingFirst = $this->order->tracking()->first();
        $trackingLast = $this->order->address;
        $trackingCurrent = $this->order->tracking()->latest()->first();

        return[
            'rep_order_id' => $this->order->id,
            'first' => [
                'latitude' => $trackingFirst?->latitude,
                'longitude' => $trackingFirst?->longitude
            ],
            'last' => [
                'latitude' => $trackingLast?->latitude,
                'longitude' => $trackingLast?->longitude
            ],
            'current' => [
                'latitude' => $trackingCurrent?->latitude,
                'longitude' => $trackingCurrent?->longitude
            ]
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'rep-order.track';
    }
}
