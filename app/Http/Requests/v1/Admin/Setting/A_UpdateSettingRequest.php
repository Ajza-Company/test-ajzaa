<?php

namespace App\Http\Requests\v1\Admin\Setting;

use Illuminate\Foundation\Http\FormRequest;

class A_UpdateSettingRequest extends FormRequest
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
            'setting' => 'required|array|min:1',
        ];
    }
    
}