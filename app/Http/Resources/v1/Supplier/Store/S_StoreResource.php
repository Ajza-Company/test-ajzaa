<?php

namespace App\Http\Resources\v1\Supplier\Store;

use App\Http\Resources\v1\Supplier\StoreHour\S_StoreHourResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class S_StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->merge(S_ShortStoreResource::make($this)),
            'state_id' => encodeString($this->area?->state_id),
            'area_id' => encodeString($this->area_id),
            'address_url' => $this->address_url,
            'phone_number' => $this->phone_number
        ];
    }
}
