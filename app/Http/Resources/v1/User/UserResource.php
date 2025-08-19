<?php

namespace App\Http\Resources\v1\User;

use App\Enums\RoleEnum;
use App\Http\Resources\v1\Frontend\Category\F_CategoryResource;
use App\Http\Resources\v1\Frontend\State\F_StateResource;
use App\Http\Resources\v1\Supplier\Store\S_ShortStoreResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'isRegistered' => (bool)$this->is_registered,
            'deletionStatus' => $this->deletion_status,
            'deletionRequestedAt' => $this->deletion_requested_at,
            'deletionReason' => $this->deletion_reason,
            'canMakeOrders' => $this->canMakeOrders(),
            'canAccessDashboard' => $this->canAccessDashboard(),
            'category' => $this->whenLoaded('company', function () {return F_CategoryResource::make($this->company->category);}),
            'role' => $this->whenLoaded('roles', function () {
                return $this->roles->first()?->name;
            }),
            'permissions' => $this->when(
                $this->relationLoaded('roles') &&
                ($this->roles->first()?->name === RoleEnum::SUPPLIER ||
                    $this->roles->first()?->name === RoleEnum::ADMIN),
                function () {
                    return $this->permissions()->pluck('name')->toArray();
                }
            ),
            'stores' => $this->whenLoaded('stores', S_ShortStoreResource::collection($this->stores)),
            'city' => $this->whenLoaded('state', function () {return F_StateResource::make($this->state);}),
        ];
    }
}
