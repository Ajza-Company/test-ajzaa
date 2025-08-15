<?php

namespace App\Http\Resources\v1\General\SupportChat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class G_SupportChatMessageResource extends JsonResource
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
            'message' => $this->message,
            'message_type' => $this->message_type,
            'attachment' => $this->when($this->attachment, function () {
                return Storage::url($this->attachment);
            }),
            'status' => $this->chat->status,
            'is_from_support' => (bool) $this->is_from_support,
            'sender' => $this->whenLoaded('sender', function () {
                return [
                    'id' => encodeString($this->sender->id),
                    'name' => $this->sender->name,
                    'email' => $this->sender->email,
                    'avatar' => $this->sender->avatar
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
