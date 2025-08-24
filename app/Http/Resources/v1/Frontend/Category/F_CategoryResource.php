<?php

namespace App\Http\Resources\v1\Frontend\Category;

use App\Http\Resources\v1\Admin\Variant\A_ShortVariantResource;
use App\Http\Resources\v1\Frontend\Store\F_ShortStoreResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class F_CategoryResource extends JsonResource
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
            'parent' => $this->whenLoaded('parent'),
            'stores' => F_ShortStoreResource::collection($this->whenLoaded('stores')),
            'stores_count' => $this->when(isset($this->stores_count), $this->stores_count),
            'variants' => A_ShortVariantResource::collection($this->whenLoaded('variants'))
        ];
    }
}
