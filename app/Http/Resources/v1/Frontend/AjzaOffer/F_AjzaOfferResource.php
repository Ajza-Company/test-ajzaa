<?php

namespace App\Http\Resources\v1\Frontend\AjzaOffer;

use App\Http\Resources\v1\Frontend\Store\F_ShortStoreResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class F_AjzaOfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->merge(F_ShortAjzaOfferResource::make($this)),
            'description' => $this->localized?->description,
            'store' => $this->whenLoaded('store', new F_ShortStoreResource($this->store))
        ];
    }
}
