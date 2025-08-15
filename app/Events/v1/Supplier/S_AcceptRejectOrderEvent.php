<?php

namespace App\Events\v1\Supplier;

use App\Http\Resources\v1\Frontend\Order\F_ShortOrderResource;
use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class S_AcceptRejectOrderEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(private Order $order)
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
            new PrivateChannel('orders'),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return F_ShortOrderResource::make($this->order)->resolve();
    }

    /*public function broadcastWhen(): bool
    {
        return auth()->user()->can('manage-orders');
    }*/

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'orders.toggle';
    }
}
