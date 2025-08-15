<?php

namespace App\Http\Requests\v1\Admin\RepSales;

use App\Enums\RoleEnum;
use App\Models\User;
use App\Traits\DecodesInputTrait;
use App\Enums\EncodingMethodsEnum;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class F_CreateRepSalesRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'full_mobile' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    return User::where('full_mobile', $value)->whereDoesntHave('roles', function ($query) { $query->whereIn('name', [RoleEnum::SUPPLIER, RoleEnum::REPRESENTATIVE]); })->exists();
                },
            ],
            'gender' => 'required|string|in:male,female',
            'avatar' => 'sometimes|file|max:2408',
            'password' => 'required|min:8',
            'city_id' => 'required|integer|exists:states,id',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->decodeInput('city_id');
    }

}
