<?php

namespace App\Http\Resources\v1\Frontend\Country;

use App\Enums\EncodingMethodsEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class F_CountryResource extends JsonResource
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
            'iso2' => $this->iso2,
            'phone_code' => $this->phone_code,
            'emoji' => $this->emoji,
            'is_active' => (bool)$this->is_active
        ];
    }
}
