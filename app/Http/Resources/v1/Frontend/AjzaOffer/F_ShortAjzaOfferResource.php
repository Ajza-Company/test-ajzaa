<?php

namespace App\Http\Resources\v1\Frontend\AjzaOffer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class F_ShortAjzaOfferResource extends JsonResource
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
            'title' => $this->localized?->title,
            'price' => round($this->price, 2),
            'old_price' => $this->old_price,
            'image' => getFullUrl($this->image)
        ];
    }
}
