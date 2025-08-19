<?php

use App\Enums\RoleEnum;
use App\Models\RepChat;
use App\Models\RepOrder;
use App\Models\SupportChat;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::routes(['middleware' => 'auth:sanctum']);

/*Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});*/

Broadcast::channel('orders', function ($user) {
    return true;
});

Broadcast::channel('repair.chat.{chatId}', function ($user, $chatId) {
    try {
        $decodedChatId = decodeString($chatId);
        $chat = RepChat::findOrFail($decodedChatId);

        $authorized = $user->id === $chat->user1_id || $user->id === $chat->user2_id;

        \Log::info('Channel Auth:', [
            'user_id' => $user->id,
            'chat_id' => $chatId,
            'authorized' => $authorized
        ]);

        return $authorized;
    } catch (\Exception $e) {
        \Log::error('Channel Auth Error:', [
            'error' => $e->getMessage(),
            'chat_id' => $chatId
        ]);
        return false;
    }
});

Broadcast::channel('rep-order', function ($user) {
    \Log::info('Channel Auth:', [
        'user_id' => $user->id
    ]);
    return true;
});

Broadcast::channel('rep-order.{orderId}', function ($user, $orderId) {
    try {
        $decodedOrderId = decodeString($orderId);
        $order = RepOrder::findOrFail($decodedOrderId);

        // Allow access if the user is the chat owner or an admin
        $authorized = $user->id === $order->user_id || $order->rep_id === $user->id;

        \Log::info('Rep Order Tracking Channel Auth:', [
            'user_id' => $user->id,
            'authorized' => $authorized
        ]);

        return $authorized;
    } catch (\Exception $e) {
        \Log::error('Rep Order Tracking Channel Auth Error:', [
            'error' => $e->getMessage(),
        ]);
        return false;
    }
});

Broadcast::channel('support.chat.{chatId}', function ($user, $chatId) {
    try {
        $decodedChatId = decodeString($chatId);
        $chat = SupportChat::findOrFail($decodedChatId);

        // Allow access if the user is the chat owner or an admin
        $authorized = $user->id === $chat->user_id || $user->hasRole('admin');

        \Log::info('Support Chat Channel Auth:', [
            'user_id' => $user->id,
            'chat_id' => $chatId,
            'authorized' => $authorized
        ]);

        return $authorized;
    } catch (\Exception $e) {
        \Log::error('Support Chat Channel Auth Error:', [
            'error' => $e->getMessage(),
            'chat_id' => $chatId
        ]);
        return false;
    }
});

// Private chat channels for general chat
Broadcast::channel('private-chat.{chatId}', function ($user, $chatId) {
    // التحقق من أن المستخدم يمكنه الوصول لهذا الـ chat
    // يمكنك إضافة logic أكثر تعقيداً هنا
    return true;
});

// Private user channels for notifications
Broadcast::channel('private-user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

// Private order channels for order updates
Broadcast::channel('private-order.{orderId}', function ($user, $orderId) {
    // التحقق من أن المستخدم يمكنه الوصول لهذا الـ order
    // يمكنك إضافة logic أكثر تعقيداً هنا
    return true;
});

// Test channel for debugging
Broadcast::channel('test-channel', function ($user) {
    return true;
});
