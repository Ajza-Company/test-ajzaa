<?php

namespace App\Events\v1\Frontend;

use App\Http\Resources\v1\Frontend\RepOrder\F_ShortRepOrderResource;
use App\Http\Resources\v1\Supplier\RepOrder\S_ShortRepOrderResource;
use App\Models\RepOrder;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class F_CreateRepOrderEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public RepOrder $order;

    /**
     * Create a new event instance.
     */
    public function __construct(RepOrder $order)
    {
        $this->order = $order;
        \Log::info('order created' . json_encode($this->order));
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('rep-order'),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return (S_ShortRepOrderResource::make($this->order))->resolve();
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'order.created';
    }
}
