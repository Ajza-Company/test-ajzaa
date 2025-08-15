<?php

namespace App\Http\Resources\v1\Supplier\Offer;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class S_ShortOfferResource extends JsonResource
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
            'type' => $this->type,
            'discount' => $this->discount,
            'expires_at' => Carbon::parse($this->expires_at)->locale(app()->getLocale())->translatedFormat('d M, Y h:i A'),
            'is_expired' => $this->expires_at < Carbon::now()
        ];
    }
}
