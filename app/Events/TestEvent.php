<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TestEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('test-channel');
    }

    // Optional: Customize the event name
    public function broadcastAs()
    {
        return 'test.message';
    }

    // Optional: Customize the data
    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'hello' => 'world',
            'timestamp' => now()->toISOString(),
        ];
    }
}
