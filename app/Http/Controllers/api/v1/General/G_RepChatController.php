<?php

namespace App\Http\Controllers\api\v1\General;

use App\Enums\MessageTypeEnum;
use App\Events\v1\General\G_RepMessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\General\RepChat\G_SendMessageRequest;
use App\Http\Requests\v1\General\RepChat\G_SendOfferRequest;
use App\Http\Requests\v1\General\RepChat\G_UpdateOfferRequest;
use App\Http\Resources\v1\General\RepChat\G_RepChatMessageResource;
use App\Http\Resources\v1\General\RepChat\G_RepChatResource;
use App\Http\Resources\v1\General\RepChat\G_RepOfferResource;
use App\Models\RepChat;
use App\Models\RepChatMessage;
use App\Models\RepOffer;
use Illuminate\Http\Request;

class G_RepChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = auth('api')->id();
        $chats = RepChat::where('user1_id', $user_id)
            ->orWhere('user2_id', $user_id)
            ->with(['user1', 'user2', 'latestMessage', 'order'])
            ->latest()
            ->filter(\request())
            ->paginate();

        return G_RepChatResource::collection($chats);
    }

    /**
     * Send a new message
     */
    public function sendMessage(G_SendMessageRequest $request, string $chat_id)
    {
        $chat = RepChat::findOrFail(decodeString($chat_id));

        $message_type = MessageTypeEnum::TEXT;
        if ($request->has('message_type')) {
            $message_type = $request->message_type;
        }

        $message = new RepChatMessage([
            'sender_id' => auth('api')->id(),
            'message_type' => $request->hasFile('attachment') ? MessageTypeEnum::ATTACHMENT : $message_type,
            'message' => $request->message,
            'is_invoice' => $request->has('is_invoice') && (bool)$request->is_invoice
        ]);

        if ($request->hasFile('attachment')) {
            $message->attachment = $request->file('attachment')
                ->store('chat-attachments', 'public');
        }

        $chat->messages()->save($message);
        $message->load(['sender','chat','chat.user1','chat.user2']);

        broadcast(new G_RepMessageSent($message))->toOthers();

        return G_RepChatMessageResource::make($message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $chat_id)
    {
        $chat = RepChat::findOrFail(decodeString($chat_id));

        $chat->load(['user1', 'user2']);

        return G_RepChatResource::make($chat->load('order.tracking'));
    }

    /**
     * Get chat messages
     */
    public function messages(string $chat_id)
    {
        $chat = RepChat::findOrFail(decodeString($chat_id));

        // Check if any message in this chat has an accepted offer
        $hasAcceptedOffer = $chat->messages()
            ->whereHas('offer', function($query) {
                $query->where('status', 'accepted');
            })
            ->exists();

        $hasInvoice = $chat->messages()->whereIsInvoice(true)->exists();
        $startDelivery = $chat->messages()->where('message_type', MessageTypeEnum::START_DELIVERY)->exists();

        $messages = $chat->messages()
            ->whereIsHidden(false)
            ->where('message_type', '!=', MessageTypeEnum::ENDED)
            ->with(['sender', 'offer', 'chat', 'chat.user1','chat.user2'])
            ->latest()
            ->paginate();

        return G_RepChatMessageResource::collection($messages)
            ->additional(['meta' => [
                'has_accepted_offer' => $hasAcceptedOffer,
                'has_invoice' => $hasInvoice,
                'start_delivery' => $startDelivery
            ]]);
    }

    /**
     * Send a new offer
     */
    public function sendOffer(G_SendOfferRequest $request, string $chat_id)
    {
        $chat = RepChat::findOrFail(decodeString($chat_id));

        $offer = RepOffer::create([
            'rep_order_id' => $chat->rep_order_id,
            'price' => $request->price
        ]);

        $message = new RepChatMessage([
            'sender_id' => auth('api')->id(),
            'message_type' => MessageTypeEnum::OFFER,
            'rep_offer_id' => $offer->id
        ]);

        $chat->messages()->save($message);
        $message->load(['sender', 'offer','chat','chat.user1','chat.user2']);

        broadcast(new G_RepMessageSent($message))->toOthers();

        return G_RepChatMessageResource::make($message);
    }

    /**
     * Update offer status
     */
    public function updateOffer(G_UpdateOfferRequest $request, string $rep_offer_id)
    {
        $data = $request->validated();
        $user = auth('api')->id();
        \Log::info('$rep_offer_id: '. $rep_offer_id . ' - $data: '. json_encode($data));
        $offer = RepOffer::findOrFail(decodeString($rep_offer_id));
        $chat = RepChat::findOrFail($data['chat_id']);
        $offer->update([
            'status' => $data['status'],
            'rep_user_id'=>$user
        ]);

        $message = new RepChatMessage([
            'sender_id' => $user,
            'message_type' => MessageTypeEnum::TEXT,
            'message' => $data['status'] == 'accepted' ? 'تم قبول العرض بقيمة '. $offer->price : 'تم رفض العرض بقيمة '. $offer->price,
            'rep_offer_id' => $offer->id,
            'is_hidden' => true
        ]);

        $chat->messages()->save($message);
        $message->load(['sender', 'offer']);

        broadcast(new G_RepMessageSent($message))->toOthers();

        return G_RepOfferResource::make($offer);
    }
}
