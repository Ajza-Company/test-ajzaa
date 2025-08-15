<?php

namespace App\Http\Controllers\api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\v1\General\SupportChat\G_SupportChatResource;
use App\Models\SupportChat;
use Illuminate\Http\Request;

class A_SupportChatController extends Controller
{
    /**
     * Display a listing of all support chats.
     */
    public function index()
    {
        $chats = SupportChat::with(['user', 'latestMessage'])
            ->filter(request())
            ->orderBy('created_at')
            ->latest()
            ->paginate();

        return G_SupportChatResource::collection($chats);
    }

    /**
     * Update the status of a support chat.
     */
    public function updateStatus(Request $request, string $chat_id)
    {
        $request->validate([
            'status' => 'required|in:open,closed,pending'
        ]);

        $chat = SupportChat::findOrFail(decodeString($chat_id));
        $chat->update(['status' => $request->status]);
        $chat->load(['user', 'latestMessage']);

        return G_SupportChatResource::make($chat);
    }
}
