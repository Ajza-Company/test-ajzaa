<?php

namespace App\Http\Resources\v1\Admin\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class A_ProductLocalesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->localized->name,
            'description' => $this->localized->description,
            'locale' => [
                'id' => encodeString($this->locale?->id),
                'name' => $this->locale?->name
            ]
        ];
    }
}
