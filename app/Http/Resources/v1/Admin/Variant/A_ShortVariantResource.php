<?php

namespace App\Http\Resources\v1\Admin\Variant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class A_ShortVariantResource extends JsonResource
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
            'name' => $this->localized?->name
        ];
    }
}
