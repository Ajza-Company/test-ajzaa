<?php

namespace App\Http\Requests\v1\Frontend\Auth;

use Illuminate\Foundation\Http\FormRequest;

class F_UpdateAccountRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'gender' => 'required|string|in:male,female',
        ];
    }
}
