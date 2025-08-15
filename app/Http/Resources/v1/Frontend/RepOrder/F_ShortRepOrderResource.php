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
            $this->merge($this->whenLoaded('repChats', function (){
                $chat = $this->repChats()->latest()->first();
                return [
                    'id' => encodeString($chat?->id),
                    'rep_order_id' => encodeString($chat?->rep_order_id),
                    'name' => $chat?->user1?->name,
                    'message' => G_RepChatMessageResource::make($chat?->latestMessage->load(['sender','chat','chat.user1','chat.user2']))
                ];
            }))
        ];
    }
}
