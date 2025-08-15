<?php

namespace App\Http\Resources\v1\Frontend\Favorite;

use App\Http\Resources\v1\Frontend\Product\F_ShortProductResource;
use App\Http\Resources\v1\Frontend\Store\F_ShortStoreResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class F_FavoriteResource extends JsonResource
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
            'product' => $this->whenLoaded('storeProduct', F_ShortProductResource::make($this->storeProduct)),
        ];
    }
}
