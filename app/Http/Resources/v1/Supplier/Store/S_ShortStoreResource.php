<?php

namespace App\Http\Resources\v1\Supplier\Store;

use App\Http\Resources\v1\Frontend\Area\F_AreaResource;
use App\Http\Resources\v1\Supplier\StoreHour\S_StoreHourResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class S_ShortStoreResource extends JsonResource
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
            'phone' => $this->phone_number,
            'address_url' => $this->address_url,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'address' => $this->address,
            'isActive' => (bool)$this->is_active,
            'area' => $this->whenLoaded('area', F_AreaResource::make($this->area)),
            'hours' => $this->whenLoaded('hours', S_StoreHourResource::collection($this->hours)),
            'category' => encodeString($this->category?->category_id)
        ];
    }
}
