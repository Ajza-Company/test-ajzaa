<?php

namespace App\Http\Resources\v1\Frontend\Address;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class F_ShortAddressResource extends JsonResource
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
            'name' => $this->name,
            'is_default' => (bool)$this->is_default
        ];
    }
}
