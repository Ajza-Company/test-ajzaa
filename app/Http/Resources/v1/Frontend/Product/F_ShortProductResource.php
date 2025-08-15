<?php

namespace App\Http\Resources\v1\Frontend\Product;

use App\Http\Resources\v1\Frontend\Store\F_ShortStoreResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Str;

class F_ShortProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $oldprice = $this->price;
        $price = $this->price;

        if ($this->relationLoaded('offer') && $this->offer !== null) {
            if ($this->offer?->type === 'fixed') {
                $price = $this->price - $this->offer?->discount;
            } else {
                $price = $this->price - ($this->price * $this->offer?->discount) / 100;
            }
        }
        return [
            'id' => encodeString($this->id),
            'store_id' => encodeString($this->store_id),
            'name' => $this->product?->localized?->name,
            'price' => round($price, 2),
            'old_price' => $this->when($this->relationLoaded('offer') && $this->offer !== null, function () use ($oldprice) {
                return round($oldprice, 2);
            }),
            'currency' => $this->store?->company?->country?->localized?->currency_code,
            'discount_type' => $this->whenLoaded('offer', function () {
                return $this->offer?->type;
            }),
            'discount' => $this->whenLoaded('offer', function (){
                return trans($this->offer?->type === 'fixed' ? 'general.product_discount' : 'general.product_discount_percentage', ['discount' => $this->offer?->discount ?? 0]);
            }),
            'image' => Str::startsWith($this->product?->image, 'PartsPictures') ? 'https://s3.me-south-1.amazonaws.com/images.rafraf.com/' . $this->product?->image : getFullUrl($this->product?->image),
            'is_favorite' => $this->when($this->relationLoaded('favorite'), function () {
                return $this->favorite !== null;
            }),
            'store' => $this->whenLoaded('store', new F_ShortStoreResource($this->store))
        ];
    }
}
