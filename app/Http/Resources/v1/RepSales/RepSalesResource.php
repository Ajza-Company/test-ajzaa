<?php

namespace App\Http\Resources\v1\RepSales;

use App\Enums\RoleEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\v1\User\ShortUserResource;
use App\Http\Resources\v1\Supplier\Store\S_ShortStoreResource;

class RepSalesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->merge(ShortUserResource::make($this)),
            'email' => $this->email,
            'fullMobile' => $this->full_mobile,
            'gender' => $this->gender,
            'avatar' => $this->avatar,
            'isRegistered' => (bool)$this->is_registered,
            'role' => $this->whenLoaded('roles', function () {
                return $this->roles->first()?->name;
            }),
            'permissions' => $this->when($this->relationLoaded('roles') && $this->roles->first()?->name === RoleEnum::SUPPLIER, function () {
                return $this->permissions()->pluck('name')->toArray();
            }),
            'stores' => $this->whenLoaded('stores', S_ShortStoreResource::collection($this->company->stores))
        ];
    }
}
