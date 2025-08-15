<?php

namespace App\Http\Resources\v1\General\RepChat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class G_RepOfferResource extends JsonResource
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
            'price' => round($this->price, 2),
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
