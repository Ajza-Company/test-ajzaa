<?php

namespace App\Http\Resources\v1\Admin\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Str;

class A_ShortProductResource extends JsonResource
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
            'name' => $this->localized->name,
            'description' => $this->localized->description,
            'category' => $this->category,
            'part_number' => $this->part_number,
            'quantity' => $this->storeProduct?->quantity,
            'image' => Str::startsWith($this->image, 'PartsPictures') ? 'https://s3.me-south-1.amazonaws.com/images.rafraf.com/' . $this->image : getFullUrl($this->image),
            'price' => $this->price,
            'is_original' => (bool) $this->is_original,
            'is_active' => (bool) $this->is_active,
            //'locales' => $this->whenLoaded('locales', A_ProductLocalesResource::collection($this->locales)),
            'variants' => $this->variant ? $this->variant->map(function($variant) {
                return [
                    'id' => encodeString($variant->variantCategory->id),
                    'variant_name' => $variant->variantCategory?->localized?->name,
                    'variant_value' => $variant->value,
                ];
            }) : [],
        ];
    }
}
