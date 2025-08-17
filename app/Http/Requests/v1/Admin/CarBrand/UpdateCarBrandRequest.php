<?php

namespace App\Http\Requests\v1\Admin\CarBrand;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCarBrandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Decode the car brand ID from the route
        $encodedId = $this->route('id');
        $carBrandId = decodeString($encodedId);
        
        return [
            'name' => 'nullable|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'external_id' => [
                'nullable',
                'integer',
                Rule::unique('car_brands', 'external_id')->ignore($carBrandId)
            ],
            'is_active' => 'boolean',
            'logo' => 'nullable|max:10240'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.max' => 'Car brand name cannot exceed 255 characters',
            'name_ar.max' => 'Arabic name cannot exceed 255 characters',
            'external_id.unique' => 'External ID already exists',
            'logo.max' => 'Logo size cannot exceed 10MB'
        ];
    }
}
