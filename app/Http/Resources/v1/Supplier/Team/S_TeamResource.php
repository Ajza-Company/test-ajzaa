<?php

namespace App\Http\Resources\v1\Supplier\Team;

use App\Http\Resources\v1\Supplier\Store\S_ShortStoreResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class S_TeamResource extends JsonResource
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
            'full_mobile' => $this->full_mobile,
            'avatar' => getFullUrl($this->avatar), // إضافة صورة البروفايل
            "is_active" => (bool)$this->is_active,
            'store' => $this->whenLoaded('store', S_ShortStoreResource::make($this->store)),
            'permissions' => $this->whenLoaded('permissions', $this->permissions()->pluck('name')->toArray())
        ];
    }
}
