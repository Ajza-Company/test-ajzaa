<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SimpleTestEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $timestamp;

    public function __construct($message = 'Test message')
    {
        $this->message = $message;
        $this->timestamp = now()->toISOString();
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('test-channel'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
            'timestamp' => $this->timestamp,
            'test' => true
        ];
    }

    public function broadcastAs(): string
    {
        return 'test.message';
    }
}
