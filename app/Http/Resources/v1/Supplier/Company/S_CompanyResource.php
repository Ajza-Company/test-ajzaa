<?php

namespace App\Http\Resources\v1\Supplier\Company;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
            'id' => $this->id,
            'name' => $this->localized->first()?->name ?? 'N/A',
            'description' => $this->localized->first()?->description,
            'email' => $this->email,
            'commercial_register' => $this->commercial_register,
            'vat_number' => $this->vat_number,
            'is_approved' => $this->is_approved,
            'is_active' => $this->is_active,
            'logo' => $this->logo ? Storage::url($this->logo) : null,
            'cover_image' => $this->cover_image ? Storage::url($this->cover_image) : null,
            'commercial_register_file' => $this->commercial_register_file ? Storage::url($this->commercial_register_file) : null,
            'category' => [
                'id' => $this->category?->id,
                'name' => $this->category?->localized?->first()?->name ?? 'N/A'
            ],
            'country' => [
                'id' => $this->country?->id,
                'name' => $this->country?->localized?->first()?->name ?? 'N/A'
            ],
            'localized' => $this->locales ? $this->locales->map(function ($locale) {
                return [
                    'locale_id' => $locale->locale_id,
                    'locale_code' => $locale->locale->code ?? 'N/A',
                    'name' => $locale->name,
                    'description' => $locale->description
                ];
            }) : [],
            'user' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'email' => $this->user?->email,
                'full_mobile' => $this->user?->full_mobile,
                'avatar' => $this->user?->avatar ? Storage::url($this->user->avatar) : null,
                'gender' => $this->user?->gender,
                'preferred_language' => $this->user?->preferred_language,
                'is_registered' => $this->user?->is_registered,
                'created_at' => $this->user?->created_at,
                'updated_at' => $this->user?->updated_at
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
