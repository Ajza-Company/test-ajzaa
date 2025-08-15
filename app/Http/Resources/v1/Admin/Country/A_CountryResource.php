<?php

namespace App\Http\Resources\v1\Admin\Country;

use App\Http\Resources\v1\Admin\State\A_ShortStateResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class A_CountryResource extends JsonResource
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
            'states' => $this->whenLoaded('states', A_ShortStateResource::collection($this->states))
        ];
    }
}
