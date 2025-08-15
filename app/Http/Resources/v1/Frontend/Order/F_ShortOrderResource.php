<?php

namespace App\Http\Resources\v1\Frontend\Order;

use App\Http\Resources\v1\Frontend\Address\F_AddressResource;
use App\Http\Resources\v1\Frontend\Address\F_ShortAddressResource;
use App\Http\Resources\v1\Frontend\OrderProduct\F_ShortOrderProductResource;
use App\Http\Resources\v1\Frontend\Store\F_ShortStoreResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class F_ShortOrderResource extends JsonResource
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
            'order_number' => $this->order_id,
            'status' => $this->status,
            'delivery_method' => $this->delivery_method,
            'date' => $this->created_at,
            'store' => $this->whenLoaded('store', new F_ShortStoreResource($this->store)),
            'products' => $this->whenLoaded('orderProducts', F_ShortOrderProductResource::collection($this->orderProducts)),
            'address' => $this->whenLoaded('address', new F_AddressResource($this->address)),
        ];
    }
}
