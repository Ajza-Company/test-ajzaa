<?php

namespace App\Http\Requests\v1\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Traits\DecodesInputTrait;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->id),
            ],
            'full_mobile' => [
                'required',
                'string',
                Rule::unique('users', 'full_mobile')->ignore($this->id),
            ],
            'password' => [
                'sometimes', 'string',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
            ],
            'avatar' => 'sometimes|file|max:2408',
            'permissions'=>'array|min:1',
            'permissions.*'=>'required_with:permissions|string'
        ];
    }
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => decodeString($this->route('id')),
        ]);
    }
}
