<?php

namespace App\Http\Resources\v1\Frontend\StoreProduct;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class F_ShortStoreProductResource extends JsonResource
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
            'name' => $this->product?->localized?->name,
            'image' => 'https://s3.me-south-1.amazonaws.com/images.rafraf.com/' . $this->product?->image,
        ];
    }
}
