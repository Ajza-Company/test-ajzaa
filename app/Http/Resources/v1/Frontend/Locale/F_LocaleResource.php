<?php

namespace App\Http\Resources\v1\Frontend\Locale;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class F_LocaleResource extends JsonResource
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
            'locale' => $this->locale,
            'isDefault' => (bool) $this->is_default
        ];
    }
}
