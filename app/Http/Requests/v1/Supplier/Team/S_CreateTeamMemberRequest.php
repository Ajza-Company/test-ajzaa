<?php

namespace App\Http\Requests\v1\Supplier\Team;

use App\Enums\RoleEnum;
use App\Models\User;
use App\Traits\DecodesInputTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class S_CreateTeamMemberRequest extends FormRequest
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
            'data.name' => 'required|string',
            'full_mobile' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                        return User::where('full_mobile', $value)->whereDoesntHave('roles', function ($query) { $query->whereIn('name', [RoleEnum::SUPPLIER, RoleEnum::REPRESENTATIVE, RoleEnum::ADMIN]); })->exists();
                },
            ],
            'data.password' => [
                'required', 'string',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
            ],
            'store_id' => 'required|integer|exists:stores,id',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'required|string|exists:permissions,name',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->decodeInput('store_id');
    }
}
