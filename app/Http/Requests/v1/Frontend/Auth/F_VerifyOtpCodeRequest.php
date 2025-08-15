<?php

namespace App\Http\Requests\v1\Frontend\Auth;

use Illuminate\Foundation\Http\FormRequest;

class F_VerifyOtpCodeRequest extends FormRequest
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
            'full_mobile' => 'required|string',
            'code' => 'required|integer',
            'fcm_token' => 'sometimes|string'
        ];
    }
}
