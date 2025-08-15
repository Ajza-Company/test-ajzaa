<?php

namespace App\Http\Resources\v1\Frontend\Address;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class F_AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->merge(F_ShortAddressResource::make($this)),
            'house_number' => $this->house_number,
            'level' => $this->level,
            'apartment_number' => $this->apartment_number,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }
}
