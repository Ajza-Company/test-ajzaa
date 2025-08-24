<?php

namespace App\Http\Requests\v1\Admin\Company;

use App\Traits\DecodesInputTrait;
use Illuminate\Foundation\Http\FormRequest;

class A_UpdateCompanyRequest extends FormRequest
{
    use DecodesInputTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|string|max:20',
            'commercial_register' => 'sometimes|string|max:255',
            'tax_number' => 'sometimes|string|max:255',
            'country_id' => 'sometimes|exists:countries,id',
            'state_id' => 'sometimes|exists:states,id',
            'city_id' => 'sometimes|exists:cities,id',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cover_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'sometimes|boolean',
            'is_approved' => 'sometimes|boolean',
            'locales' => 'sometimes|array',
            'locales.ar.name' => 'required_with:locales|string|max:255',
            'locales.ar.description' => 'nullable|string|max:1000',
            'locales.en.name' => 'required_with:locales|string|max:255',
            'locales.en.description' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'locales.ar.name.required_with' => 'Company name in Arabic is required when updating locales',
            'locales.en.name.required_with' => 'Company name in English is required when updating locales',
            'email.email' => 'Please provide a valid email address',
            'phone.max' => 'Phone number cannot exceed 20 characters',
            'image.max' => 'Image size cannot exceed 2MB',
            'cover_image.max' => 'Cover image size cannot exceed 2MB',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->decodeInput('country_id');
        $this->decodeInput('state_id');
        $this->decodeInput('city_id');
    }
}
