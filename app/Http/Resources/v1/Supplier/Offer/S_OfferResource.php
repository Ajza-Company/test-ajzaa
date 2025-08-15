<?php

namespace App\Http\Resources\v1\Supplier\Offer;

use App\Http\Resources\v1\Supplier\StoreProduct\S_ShortStoreProductResource;
use App\Http\Resources\v1\Supplier\StoreProduct\S_StoreProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class S_OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->merge(S_ShortOfferResource::make($this)),
            'product' => $this->whenLoaded('storeProduct', S_ShortStoreProductResource::make($this->storeProduct)),
        ];
    }
}
