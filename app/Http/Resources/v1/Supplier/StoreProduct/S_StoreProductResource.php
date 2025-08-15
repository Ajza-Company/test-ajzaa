<?php

namespace App\Http\Resources\v1\Supplier\StoreProduct;

use App\Http\Resources\v1\Supplier\Offer\S_ShortOfferResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class S_StoreProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->merge(S_ShortStoreProductResource::make($this))
        ];
    }
}
