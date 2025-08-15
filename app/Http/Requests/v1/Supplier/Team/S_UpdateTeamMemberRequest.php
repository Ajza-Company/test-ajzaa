<?php

namespace App\Http\Requests\v1\Supplier\Team;

use App\Traits\DecodesInputTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class S_UpdateTeamMemberRequest extends FormRequest
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
            'data.name' => 'sometimes|string',
            'data.full_mobile' => 'sometimes|string',
              'data.password' => [
                'sometimes', 'string',
                  Password::min(8)
                    ->mixedCase()
                    ->numbers()
              ],
            'data.is_active' => 'sometimes|boolean',
            'permissions' => 'sometimes|array|min:1',
            'permissions.*' => 'required_with:permissions|string|exists:permissions,name',
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
