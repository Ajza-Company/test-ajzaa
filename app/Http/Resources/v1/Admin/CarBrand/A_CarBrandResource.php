<?php

namespace App\Http\Resources\v1\Admin\CarBrand;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class A_CarBrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => encodeString($this->id),
            'name' => $this->english_name,
            'name_ar' => $this->arabic_name,
            'external_id' => $this->external_id,
            'is_active' => $this->is_active,
            'logo' => $this->logo_url,
            'car_models' => $this->whenLoaded('carModels', function () {
                return $this->carModels->map(function ($model) {
                    return [
                        'id' => encodeString($model->id),
                        'name' => $model->localized?->name,
                        'name_ar' => $model->localized?->name_ar,
                        'is_active' => $model->is_active
                    ];
                });
            }),
            'car_models_count' => $this->car_models_count ?? 0,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
