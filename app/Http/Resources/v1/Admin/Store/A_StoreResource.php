<?php

namespace App\Http\Resources\v1\Admin\Store;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class A_StoreResource extends JsonResource
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
            'name' => $this->company?->localized?->name,
            'image' => $this->image,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_active' => (bool) $this->is_active,
            'can_add_products' => (bool) $this->can_add_products,
            'area' => [
                'id' => encodeString($this->area?->id),
                'name' => $this->area?->localized?->name,
                'state' => [
                    'id' => encodeString($this->area?->state?->id),
                    'name' => $this->area?->state?->localized?->name,
                ]
            ],
            'category' => [
                'id' => encodeString($this->category?->category_id),
                'name' => $this->category?->category?->localized?->name,
            ],
            'company' => [
                'id' => encodeString($this->company?->id),
                'name' => $this->company?->localized?->name,
                'email' => $this->company?->email,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
