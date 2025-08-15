<?php

namespace App\Http\Resources\v1\Frontend\Category;

use App\Http\Resources\v1\Admin\Variant\A_ShortVariantResource;
use App\Http\Resources\v1\Frontend\Store\F_ShortStoreResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class F_ShortCategoryResource extends JsonResource
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
            'name' => $this->translations->mapWithKeys(function ($translation) {
                return [
                    (string) $translation->locale?->locale => $translation->name
                ];
            }),
            'parent' => $this->whenLoaded('parent'),
            'variants' => A_ShortVariantResource::collection($this->whenLoaded('variants'))
        ];
    }
}
