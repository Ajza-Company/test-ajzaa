<?php

namespace App\Http\Resources\v1\General\RepChat;

use App\Http\Resources\v1\User\ShortUserResource;
use App\Http\Resources\v1\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class G_RepChatMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        // Check if any message in this chat has an offer with accepted status
        $hasAcceptedOffer = false;

        if ($this->relationLoaded('chat')) {
            $hasAcceptedOffer = $request->has('meta.has_accepted_offer') ?
                $request->input('meta.has_accepted_offer') :
                $this->chat->messages()
                    ?->whereHas('offer', function($query) {
                        $query->where('status', 'accepted');
                    })
                    ->exists();
        }

        return [
            'id' => encodeString($this->id),
            'sender' => ShortUserResource::make($this->whenLoaded('sender')),
            'rep_user_mobile' => $hasAcceptedOffer && $this->relationLoaded('chat') && $this->chat->relationLoaded('user1') ?
                $this->chat->user1->full_mobile : null,
            'client_user_mobile' => $hasAcceptedOffer && $this->relationLoaded('chat') && $this->chat->relationLoaded('user2') ?
                $this->chat->user2->full_mobile : null,
            'message' => $this->message,
            'message_type' => $this->message_type,
            'is_hidden' => (bool)$this->is_hidden,
            'is_invoice' => (bool)$this->is_invoice,
            'attachment' => $this->when($this->attachment, function() {
                return [
                    'url' => getFullUrl($this->attachment),
                    'filename' => basename($this->attachment)
                ];
            }),
            'offer' => new G_RepOfferResource($this->whenLoaded('offer')),
            'created_at' => $this->created_at,
        ];
    }
}
