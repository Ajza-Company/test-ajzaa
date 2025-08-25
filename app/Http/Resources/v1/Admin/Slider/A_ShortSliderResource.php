<?php

namespace App\Http\Resources\v1\Admin\Slider;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class A_ShortSliderResource extends JsonResource
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
            'image' => $this->getImageUrl(),
        ];
    }

    /**
     * Get the image URL with fallback
     */
    private function getImageUrl(): ?string
    {
        if ($this->image) {
            // Use Storage::url() which will respect the APP_URL from .env
            // This will automatically work for both local and production
            return Storage::url($this->image);
        }
        return null;
    }
}
