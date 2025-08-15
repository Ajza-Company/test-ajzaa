<?php

namespace App\Http\Controllers\api\v1\General;

use App\Enums\MessageTypeEnum;
use App\Events\v1\General\G_SupportMessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\General\SupportChat\G_CreateSupportChatRequest;
use App\Http\Requests\v1\General\SupportChat\G_SendMessageRequest;
use App\Http\Resources\v1\General\SupportChat\G_SupportChatMessageResource;
use App\Http\Resources\v1\General\SupportChat\G_SupportChatResource;
use App\Models\SupportChat;
use App\Models\SupportChatMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class G_SupportChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = auth('api')->id();
        $chats = SupportChat::where('user_id', $user_id)
            ->with(['user', 'latestMessage'])
            ->filter(\request())
            ->where('status', '!=', 'closed')
            ->latest()
            ->paginate();

        return G_SupportChatResource::collection($chats);
    }

    /**
     * Create a new support chat
     */
    public function store(G_CreateSupportChatRequest $request)
    {
        $user_id = auth('api')->id();

        $chat = SupportChat::create([
            'user_id' => $user_id,
            'subject' => $request->subject,
            'status' => 'open'
        ]);

        $message = new SupportChatMessage([
            'sender_id' => $user_id,
            'message_type' => $request->hasFile('attachment') ? MessageTypeEnum::ATTACHMENT : MessageTypeEnum::TEXT,
            'message' => $request->message,
            'is_from_support' => false
        ]);

        if ($request->hasFile('attachment')) {
            $message->attachment = $request->file('attachment')
                ->store('support-chat-attachments', 'public');
        }

        $chat->messages()->save($message);
        $chat->load(['user', 'latestMessage']);

        return G_SupportChatResource::make($chat);
    }

    /**
     * Send a new message
     */
    public function sendMessage(G_SendMessageRequest $request, string $chat_id)
    {
        $chat = SupportChat::findOrFail(decodeString($chat_id));

        if ($chat->status == 'closed') {
            return response()->json(errorResponse(message: 'Support Chat is closed'), 400);
        }

        $message = new SupportChatMessage([
            'sender_id' => auth('api')->id(),
            'message_type' => $request->hasFile('attachment') ? MessageTypeEnum::ATTACHMENT : MessageTypeEnum::TEXT,
            'message' => $request->message,
            'is_from_support' => auth('api')->user()->hasRole('admin')
        ]);

        if ($request->hasFile('attachment')) {
            $message->attachment = $request->file('attachment')
                ->store('support-chat-attachments', 'public');
        }

        $chat->messages()->save($message);
        $message->load(['sender']);

        broadcast(new G_SupportMessageSent($message))->toOthers();

        return G_SupportChatMessageResource::make($message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $chat_id)
    {
        $chat = SupportChat::findOrFail(decodeString($chat_id));

        $chat->load(['user']);

        return G_SupportChatResource::make($chat);
    }

    /**
     * Get chat messages
     */
    public function messages(string $chat_id)
    {
        $chat = SupportChat::findOrFail(decodeString($chat_id));

        $messages = $chat->messages()
            ->whereIsHidden(false)
            ->where('message_type', '!=', MessageTypeEnum::ENDED)
            ->with(['sender'])
            ->orderBy('created_at')
            ->paginate();

        return G_SupportChatMessageResource::collection($messages);
    }

    /**
     * Close the support chat
     */
    public function close(string $chat_id)
    {
        $chat = SupportChat::findOrFail(decodeString($chat_id));
        $chat->update(['status' => 'closed']);

        $message = new SupportChatMessage([
            'sender_id' => auth('api')->id(),
            'message_type' => MessageTypeEnum::ENDED,
            'message' => 'Chat closed',
            'is_from_support' => auth('api')->user()->hasRole('admin')
        ]);

        $chat->messages()->save($message);
        $chat->load(['user']);

        broadcast(new G_SupportMessageSent($message))->toOthers();

        return G_SupportChatResource::make($chat);
    }

    /**
     * Reopen the support chat
     */
    public function reopen(string $chat_id)
    {
        $chat = SupportChat::findOrFail(decodeString($chat_id));
        $chat->update(['status' => 'open']);

        $message = new SupportChatMessage([
            'sender_id' => auth('api')->id(),
            'message_type' => MessageTypeEnum::TEXT,
            'message' => 'Chat reopened',
            'is_from_support' => auth('api')->user()->hasRole('admin')
        ]);

        $chat->messages()->save($message);
        $chat->load(['user']);

        broadcast(new G_SupportMessageSent($message))->toOthers();

        return G_SupportChatResource::make($chat);
    }
}
