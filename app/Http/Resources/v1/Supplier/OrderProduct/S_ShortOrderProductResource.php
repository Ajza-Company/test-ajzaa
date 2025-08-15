<?php

namespace App\Http\Resources\v1\Supplier\OrderProduct;

use App\Http\Resources\v1\Supplier\StoreProduct\S_ShortStoreProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class S_ShortOrderProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->merge($this->whenLoaded('storeProduct', S_ShortStoreProductResource::make($this->storeProduct))),
            'price' => round($this->price, 2),
            'quantity' => $this->quantity,
        ];
    }
}
