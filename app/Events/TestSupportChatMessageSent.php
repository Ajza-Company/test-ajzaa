<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TestSupportChatMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $chatId;
    public $timestamp;

    /**
     * Create a new event instance.
     *
     * @param string $message
     * @param int $chatId
     */
    public function __construct(string $message, int $chatId)
    {
        $this->message = $message;
        $this->chatId = $chatId;
        $this->timestamp = now()->toISOString();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('private-support-chat.' . $this->chatId),
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'id' => uniqid(), // Unique ID for the test message
            'support_chat_id' => $this->chatId,
            'message' => $this->message,
            'created_at' => $this->timestamp,
            'updated_at' => $this->timestamp,
            'sender_type' => 'test', // Indicate it's a test message
            'sender_id' => null,
        ];
    }
}
