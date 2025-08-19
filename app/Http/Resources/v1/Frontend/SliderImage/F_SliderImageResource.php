<?php

namespace App\Http\Resources\v1\Frontend\SliderImage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            'image' => $this->getImageUrl(),
        ];
    }

    /**
     * Get the image URL with fallback
     */
    private function getImageUrl(): ?string
    {
        if ($this->image) {
            // Try to get URL from storage
            $url = Storage::url($this->image);
            
            // If storage URL doesn't work, construct direct URL
            if (str_contains($url, '/storage/')) {
                return 'https://dev.ajza.net/storage/' . str_replace('/storage/', '', $this->image);
            }
            
            return $url;
        }
        return null;
    }
}
