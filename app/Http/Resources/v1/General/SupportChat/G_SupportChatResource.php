<?php

namespace App\Http\Resources\v1\General\SupportChat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class G_SupportChatResource extends JsonResource
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
            'subject' => $this->subject,
            'status' => $this->status,
            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => encodeString($this->user->id),
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                    'avatar' => $this->user->avatar
                ];
            }),
            'latest_message' => $this->whenLoaded('latestMessage', function () {
                return new G_SupportChatMessageResource($this->latestMessage);
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}