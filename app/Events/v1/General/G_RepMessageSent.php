<?php

namespace App\Events\v1\General;

use App\Enums\MessageTypeEnum;
use App\Http\Resources\v1\General\RepChat\G_RepChatMessageResource;
use App\Models\RepChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class G_RepMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(RepChatMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('repair.chat.' . encodeString($this->message->rep_chat_id))
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        // Load necessary relationships
        $this->message->load(['sender', 'offer', 'chat', 'chat.user1', 'chat.user2']);

        // Get additional data
        $hasAcceptedOffer = $this->message->chat->messages()
            ->whereHas('offer', function($query) {
                $query->where('status', 'accepted');
            })
            ->exists();

        $hasInvoice = $this->message->chat->messages()
            ->whereIsInvoice(true)
            ->exists();

        $startDelivery = $this->message->chat->messages()
            ->where('message_type', MessageTypeEnum::START_DELIVERY)
            ->exists();

        // Create the resource and merge additional data
        $resource = new G_RepChatMessageResource($this->message);

        // Merge the resource data with additional data
        return array_merge($resource->resolve(), [
            'meta' => [
                'has_accepted_offer' => $hasAcceptedOffer,
                'has_invoice' => $hasInvoice,
                'start_delivery' => $startDelivery
            ]
        ]);
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }
}
