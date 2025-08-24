<?php

namespace App\Http\Resources\v1\Frontend\RepOrder;

use App\Http\Resources\v1\General\RepChat\G_RepChatMessageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class F_ShortRepOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => encodeString($this->id),
            'title' => $this->title,
            'description' => $this->description,
            'image' => getFullUrl($this->image),
            'status' => $this->status,
            'created_at' => $this->created_at,
            $this->merge($this->whenLoaded('repChats', function (){
                $chat = $this->repChats->first();
                return [
                    'chat_id' => encodeString($chat?->id),
                    'rep_order_id' => encodeString($chat?->rep_order_id),
                    'name' => $chat?->user1?->name,
                    'message' => $chat?->latestMessage ? G_RepChatMessageResource::make($chat->latestMessage->load(['sender','chat','chat.user1','chat.user2'])) : null
                ];
            }))
        ];
    }
}
