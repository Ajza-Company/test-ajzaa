<?php

namespace App\Http\Resources\v1\Supplier\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class S_ShortProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->localized?->name,
            'image' => 'https://s3.me-south-1.amazonaws.com/images.rafraf.com/' . $this->image,
        ];
    }
}
