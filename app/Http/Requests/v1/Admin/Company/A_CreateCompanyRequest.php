<?php

namespace App\Http\Requests\v1\Admin\Company;

use App\Enums\RoleEnum;
use App\Models\User;
use App\Traits\DecodesInputTrait;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class A_CreateCompanyRequest extends FormRequest
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
            'store.data.area_id' => 'required|integer|exists:areas,id',
            'store.data.address' => 'required|string|max:255',
            'store.data.address_url' => 'nullable|string|url',
            'store.data.latitude' => 'nullable|numeric',
            'store.data.longitude' => 'nullable|numeric',

            'store.hours' => 'required|array',
            'store.hours.*.day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'store.hours.*.open_time' => 'nullable|date_format:H:i',
            'store.hours.*.close_time' => 'nullable|date_format:H:i',
//            'store.hours.*.close_time' => 'nullable|date_format:H:i|after:store.hours.*.open_time',

            'user.name' => 'required|string|max:255',
            'user.email' => 'required|email|max:50|unique:users,email',
            'user.full_mobile' => [
                'required',
                'max:20',
                'string',
                function ($attribute, $value, $fail) {
                    return User::where('full_mobile', $value)->whereDoesntHave('roles', function ($query) { $query->whereIn('name', [RoleEnum::SUPPLIER, RoleEnum::REPRESENTATIVE, RoleEnum::ADMIN]); })->exists();

                },
            ],
            'user.gender' => 'nullable|in:male,female',
            'user.avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user.password' => 'required|string|min:8',
            'user.preferred_language' => 'nullable|string|max:5',

            'company.country_id' => 'required|integer|exists:countries,id',
            'company.email' => 'required|email|max:50|unique:companies,email',
            'company.car_brand_id' => 'required|array|min:1',
            'company.car_brand_id.*' => 'required_with:company.car_brand_id|integer|exists:car_brands,id',
            'company.phone' => 'sometimes|string|max:20|unique:companies,phone',
            'company.logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'company.cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'company.commercial_register' => 'sometimes|nullable|string|max:50',
            'company.vat_number' => 'nullable|string|max:50',
            'company.commercial_register_file' => 'sometimes|nullable|file|max:2048',
            'company.localized'=>'required|array|min:1',
            'company.localized.*.local_id'=>'required_with:company.localized|integer|exists:locales,id',
            'company.localized.*.name'=>'required_with:company.localized|string|max:100|min:5',
            'company.category_id'=>'required|integer|exists:categories,id'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->decodeInput('store.data.area_id');
        $this->decodeInput('company.country_id');
        $this->decodeInput('company.localized.*.local_id');
        $this->decodeInput('company.category_id');
        $this->decodeSimpleArrayInput('company.car_brand_id.*');
    }
}
