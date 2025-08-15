<?php

namespace App\Http\Resources\v1\Admin\Company;

use App\Http\Resources\v1\Admin\Store\A_ShortStoreResource;
use App\Http\Resources\v1\Frontend\CarBrand\F_CarBrandResource;
use App\Http\Resources\v1\Frontend\Category\F_CategoryResource;
use App\Http\Resources\v1\User\ShortUserResource;
use App\Models\CarBrand;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class A_ShortCompanyResource extends JsonResource
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
            'name' => $this->localized->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'is_approved' => (bool) $this->is_approved,
            'is_active' => (bool) $this->is_active,
            'logo' => getFullUrl($this->logo),
            'commercial_register' => $this->commercial_register,
            'vat_number' => $this->vat_number,
            'category' => $this->whenLoaded('category', F_CategoryResource::make($this->category)),
            'stores_count' => $this->whenCounted('stores'),
            'team_count' => $this->whenCounted('usersPivot'),
            'owner' => $this->whenLoaded('user', ShortUserResource::make($this->user)),
            'stores' => $this->whenLoaded('stores', A_ShortStoreResource::collection($this->stores)),
            'car_brand' => $this->when($this->car_brand_id, function () {
                $brandIds = json_decode($this->car_brand_id, true);
                return F_CarBrandResource::collection(CarBrand::whereIn('id', $brandIds)->get());
            })
        ];
    }
}
