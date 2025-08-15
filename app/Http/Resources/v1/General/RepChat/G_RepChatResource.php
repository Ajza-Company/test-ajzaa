<?php

namespace App\Http\Resources\v1\General\RepChat;

use App\Http\Resources\v1\Supplier\RepOrder\S_ShortRepOrderResource;
use App\Http\Resources\v1\User\ShortUserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class G_RepChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => encodeString($this->id),
            'rep_order_id' => encodeString($this->rep_order_id),
            'rep_order' => S_ShortRepOrderResource::make($this->whenLoaded('order')),
            'user1' => $this->getUserResource($this->user1, $this->order),
            'user2' => $this->getUserResource($this->user2, $this->order),
            'latest_message' => G_RepChatMessageResource::make($this->whenLoaded('latestMessage')),
            'tracking_started' => $this->order->tracking !== null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    protected function getUserResource($user, $order)
    {
        if (!$user) {
            return null;
        }

        $resource = ShortUserResource::make($user);
        $resourceArray = $resource->toArray(request());
        if (!$order) {
            return $resourceArray;
        }else{
            if ($order->offers()->where('status', 'accepted')->exists()) {
                $resourceArray['full_mobile'] = $user->full_mobile;
            }
        }

        return $resourceArray;
    }
}
