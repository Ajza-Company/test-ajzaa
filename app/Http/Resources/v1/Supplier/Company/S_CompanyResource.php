<?php

namespace App\Http\Resources\v1\Supplier\Company;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class S_CompanyResource extends JsonResource
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
            'name' => $this->localized?->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'logo' => getFullUrl($this->logo),
            'cover_image' => getFullUrl($this->cover_image),
            'commercial_register' => $this->commercial_register,
            'vat_number' => $this->vat_number,
            'commercial_register_file' => $this->commercial_register_file !== null
        ];
    }
}
