<?php

namespace App\Http\Resources\v1\Frontend\OrderProduct;

use App\Http\Resources\v1\Frontend\StoreProduct\F_ShortStoreProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class F_ShortOrderProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->merge($this->whenLoaded('storeProduct', F_ShortStoreProductResource::make($this->storeProduct))),
            'price' => round($this->price, 2),
            'quantity' => $this->quantity,
        ];
    }
}
