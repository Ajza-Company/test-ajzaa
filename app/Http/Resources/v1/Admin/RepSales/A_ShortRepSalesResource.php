<?php

namespace App\Http\Resources\v1\Admin\RepSales;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class A_ShortRepSalesResource extends JsonResource
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
            'email' => $this->email,
            'full_mobile' => $this->full_mobile,
            'gender' => $this->gender,
            'avatar' => $this->avatar,
            'is_active' => (bool) $this->is_active,
            'order_rep_count' => $this->offers->count(),
            'order_rep_prices' => $this->whenLoaded('offers', function () {
                return $this->offers->where('status', 'accepted')->sum('price');
            })
        ];
    }
}
