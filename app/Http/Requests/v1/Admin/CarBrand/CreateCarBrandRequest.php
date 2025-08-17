<?php

namespace App\Http\Requests\v1\Admin\CarBrand;

use Illuminate\Foundation\Http\FormRequest;

class CreateCarBrandRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'external_id' => 'nullable|integer|unique:car_brands,external_id',
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
            'name.required' => 'Car brand name is required',
            'name.max' => 'Car brand name cannot exceed 255 characters',
            'name_ar.max' => 'Arabic name cannot exceed 255 characters',
            'external_id.unique' => 'External ID already exists',
            'logo.max' => 'Logo size cannot exceed 10MB'
        ];
    }
}
