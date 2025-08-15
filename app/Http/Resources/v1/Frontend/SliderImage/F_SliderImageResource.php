<?php

namespace App\Http\Resources\v1\Frontend\SliderImage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class F_SliderImageResource extends JsonResource
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
            'image' => $this->getFirstMediaUrl('sliders'),
        ];
    }
}
