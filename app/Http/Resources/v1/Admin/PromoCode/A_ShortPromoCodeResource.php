<?php

namespace App\Http\Resources\v1\Admin\PromoCode;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class A_ShortPromoCodeResource extends JsonResource
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
            'code' => $this->code,
            'description' => $this->description,
            'type' => $this->type,
            'value' => $this->value,
            'max_uses' => $this->max_uses,
            'used_count' => $this->used_count,
            'min_order_value' => $this->min_order_value,
            'max_discount' => $this->max_discount,
            'starts_at' => $this->starts_at ? $this->starts_at->toISOString() : null,
            'expires_at' => $this->expires_at ? $this->expires_at->toISOString() : null,
        ];
    }
    
}