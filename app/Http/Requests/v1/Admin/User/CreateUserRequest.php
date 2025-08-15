<?php

namespace App\Http\Requests\v1\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'full_mobile' => 'required|string|unique:users,full_mobile',
            'password' => 'required|min:8',
            'gender' => 'required|string|in:male,female',
            'avatar' => 'sometimes|file|max:2408',
            'permissions'=>'array|min:1',
            'permissions.*'=>'required_with:permissions|string'
        ];
    }

}
