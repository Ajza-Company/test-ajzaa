<?php

namespace App\Http\Controllers\api\v1\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Events\TestEvent;

class TestBroadcastController extends Controller
{
    /**
     * Test broadcast event
     */
    public function testBroadcast(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $message = $request->input('message', 'Test message');
            $channel = $request->input('channel', 'test-channel');
            $event = $request->input('event', 'TestEvent');

            Log::info('Testing broadcast', [
                'message' => $message,
                'channel' => $channel,
                'event' => $event
            ]);

            // Broadcast to test channel
            broadcast(new TestEvent($message))->toOthers();

            return response()->json([
                'success' => true,
                'message' => 'Event broadcasted successfully',
                'data' => [
                    'message' => $message,
                    'channel' => $channel,
                    'event' => $event,
                    'timestamp' => now()
                ]
            ]);

        } catch (\Exception $ex) {
            Log::error('Broadcast test failed', [
                'error' => $ex->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Broadcast test failed',
                'error' => $ex->getMessage()
            ], 500);
        }
    }

    /**
     * Test private channel
     */
    public function testPrivateChannel(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $userId = $request->input('user_id', 1);
            $message = $request->input('message', 'Private test message');

            Log::info('Testing private channel', [
                'user_id' => $userId,
                'message' => $message
            ]);

            // Broadcast to private user channel
            broadcast(new TestEvent($message))->toOthers();

            return response()->json([
                'success' => true,
                'message' => 'Private event broadcasted successfully',
                'data' => [
                    'user_id' => $userId,
                    'message' => $message,
                    'channel' => "private-user.{$userId}",
                    'timestamp' => now()
                ]
            ]);

        } catch (\Exception $ex) {
            Log::error('Private broadcast test failed', [
                'error' => $ex->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Private broadcast test failed',
                'error' => $ex->getMessage()
            ], 500);
        }
    }
}
