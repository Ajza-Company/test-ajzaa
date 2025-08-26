<?php

use App\Http\Controllers\api\v1\Frontend\InterPayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

Route::get('payment/status', function () {
    return view('welcome');
})->name('payment.status');

// Broadcasting routes (مطلوبة للـ Pusher authentication)
Route::post('/broadcasting/auth', function () {
    return Broadcast::auth(request());
})->middleware(['auth:sanctum']);

Route::post('interpay/callback', [InterPayController::class, 'callback']);
Route::get('/payment/interpay-test', function () {
    return view('payment.interpay-test');
});

// Simple InterPay payment page
Route::get('/payment/interpay', function() {
    return view('payment.interpay-test');
});

// Test broadcasting routes
Route::get('/test-broadcast', function() {
    try {
        // Test SimpleTestEvent
        event(new App\Events\SimpleTestEvent('Hello from test route!'));
        
        // Test TestEvent
        broadcast(new App\Events\TestEvent('Hello from broadcast test!'));
        
        return response()->json([
            'success' => true,
            'message' => 'Test events dispatched successfully!',
            'events' => [
                'SimpleTestEvent' => 'Dispatched to test-channel',
                'TestEvent' => 'Broadcasted to test-channel'
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

Route::get('/test-chat-event', function() {
    try {
        // Try to dispatch a chat event if we have data
        $repMessage = App\Models\RepChatMessage::first();
        
        if ($repMessage) {
            event(new App\Events\v1\General\G_RepMessageSent($repMessage));
            return response()->json([
                'success' => true,
                'message' => 'Chat event dispatched successfully!',
                'event_type' => 'G_RepMessageSent',
                'message_id' => $repMessage->id
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No chat messages found to test with'
            ]);
        }
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

// Test route for private-support-chat.24 channel (simplified)
Route::get('/test-support-chat-24', function() {
    try {
        // Use the new dedicated event class
        event(new App\Events\TestSupportChatMessageSent(
            'Test message from test route for support chat 24!',
            24
        ));
        
        return response()->json([
            'success' => true,
            'message' => 'Support message event dispatched successfully!',
            'channel' => 'private-support-chat.24',
            'event' => 'message.sent',
            'data' => [
                'message' => 'Test message from test route for support chat 24!',
                'chat_id' => 24,
                'timestamp' => now()->toISOString()
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
});

// Simple broadcast to support chat 24 channel
Route::get('/broadcast-support-24', function() {
    try {
        // Dispatch the new named event
        event(new App\Events\TestSupportChatMessageSent(
            'Hello from broadcast route! This is a test message for support chat 24!',
            24
        ));
        
        return response()->json([
            'success' => true,
            'message' => 'Message broadcasted to support chat 24!',
            'channel' => 'private-support-chat.24',
            'event' => 'message.sent',
            'data' => [
                'message' => 'Hello from broadcast route! This is a test message for support chat 24!',
                'chat_id' => 24,
                'timestamp' => now()->toISOString()
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error broadcasting message: ' . $e->getMessage(),
            'error_details' => $e->getTraceAsString()
        ], 500);
    }
});