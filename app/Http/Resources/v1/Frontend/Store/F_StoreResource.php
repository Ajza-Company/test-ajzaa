<?php

namespace App\Http\Resources\v1\Frontend\Store;

use App\Http\Resources\v1\Frontend\Category\F_CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class F_StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->merge(F_ShortStoreResource::make($this)),
            'products_count' => $this->whenCounted('products'),
            'categories' => F_CategoryResource::collection($this->whenLoaded('categories'))
        ];
    }
}
