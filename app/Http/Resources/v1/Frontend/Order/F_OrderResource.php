<?php

namespace App\Http\Resources\v1\Frontend\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class F_OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->merge(F_ShortOrderResource::make($this)),
            'amount' => $this->amount,
            'invoice_details' => $this->whenLoaded('orderProducts', function (){
                return [
                    'amount' => $this->orderProducts->sum('amount'),
                    'discount' => $this->orderProducts->sum('discount'),
                    'delivery_fees' => 0.0,
                    'total' => $this->orderProducts->sum('amount'),
                ];
            }),
            'delivery_method' => $this->delivery_method
        ];
    }
}
