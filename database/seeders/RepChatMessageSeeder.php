<?php

namespace Database\Seeders;

use App\Models\RepChat;
use App\Models\RepChatMessage;
use App\Models\User;
use App\Enums\MessageTypeEnum;
use Illuminate\Database\Seeder;

class RepChatMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing chats
        $chats = RepChat::all();
        
        if ($chats->isEmpty()) {
            $this->command->info('No RepChats found. Please run RepChatSeeder first.');
            return;
        }

        // Get a user for sending messages
        $user = User::first();
        
        if (!$user) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }

        foreach ($chats as $chat) {
            // Create initial message
            RepChatMessage::create([
                'rep_chat_id' => $chat->id,
                'sender_id' => $chat->user1_id,
                'message_type' => MessageTypeEnum::TEXT,
                'message' => 'مرحباً! أريد طلب خدمة تشليح',
                'is_hidden' => false
            ]);

            // Create response message
            RepChatMessage::create([
                'rep_chat_id' => $chat->id,
                'sender_id' => $chat->user2_id,
                'message_type' => MessageTypeEnum::TEXT,
                'message' => 'أهلاً وسهلاً! سأقوم بمساعدتك في طلب التشليح',
                'is_hidden' => false
            ]);

            // Create offer message
            RepChatMessage::create([
                'rep_chat_id' => $chat->id,
                'sender_id' => $chat->user2_id,
                'message_type' => MessageTypeEnum::OFFER,
                'message' => 'عرض سعر: 500 ريال',
                'is_hidden' => false
            ]);
        }

        $this->command->info('Created ' . ($chats->count() * 3) . ' test messages for RepChats.');
    }
}
