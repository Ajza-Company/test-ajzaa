<?php

namespace App\Http\Requests\v1\Supplier\Company;

use App\Traits\DecodesInputTrait;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class S_UpdateCompanyRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'company.localized' => 'sometimes|array|min:1',
            'company.localized.*.local_id' => 'required_with:company.localized|integer|exists:locales,id',
            'company.localized.*.name' => 'required_with:company.localized|string|max:100|min:2',
            'company.localized.*.description' => 'nullable|string|max:500',
            'company.email' => 'sometimes|email|max:100',
            'company.commercial_register' => 'nullable|string|max:50',
            'company.vat_number' => 'nullable|string|max:50',
            'company.commercial_register_file' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'company.logo' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'company.cover_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'company.is_approved' => 'sometimes|boolean',
            'company.is_active' => 'sometimes|boolean',
            'user.name' => 'sometimes|string|max:100|min:2',
            'user.email' => 'sometimes|email|max:100',
            'user.full_mobile' => 'sometimes|string|max:20',
            'user.avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user.gender' => 'sometimes|in:male,female',
            'user.password' => 'sometimes|string|min:6|confirmed',
            'user.password_confirmation' => 'required_with:user.password|string|min:6',
            'user.preferred_language' => 'sometimes|string|in:ar,en'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->decodeInput('company.localized.*.local_id');
    }
}
