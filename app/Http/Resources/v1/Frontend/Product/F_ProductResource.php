<?php

namespace App\Http\Resources\v1\Frontend\Product;

use App\Http\Resources\v1\Frontend\Store\F_ShortStoreResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class F_ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->merge(F_ShortProductResource::make($this)),
            'partNumber' => $this->product?->part_number,
            'is_original' => (bool)$this->product?->is_original,
            'description' => $this->product?->localized?->description,
            'store' => $this->whenLoaded('store', new F_ShortStoreResource($this->store))
        ];
    }
}
