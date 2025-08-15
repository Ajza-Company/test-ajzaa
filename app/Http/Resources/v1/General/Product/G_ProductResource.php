<?php

namespace App\Http\Resources\v1\General\Product;

use App\Enums\EncodingMethodsEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class G_ProductResource extends JsonResource
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
            'name' => $this->localized?->name,
            'price' => round($this->price, 2),
            'image' => 'https://s3.me-south-1.amazonaws.com/images.rafraf.com/' . $this->image,
        ];
    }

}
