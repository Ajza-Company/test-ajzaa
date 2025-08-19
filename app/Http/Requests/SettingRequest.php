<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
            'key' => 'required|string|max:255',
            'value' => 'required',
            'type' => 'required|string|in:string,boolean,integer,json',
            'is_active' => 'boolean'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'key.required' => 'The setting key is required.',
            'key.string' => 'The setting key must be a string.',
            'key.max' => 'The setting key may not be greater than 255 characters.',
            'value.required' => 'The setting value is required.',
            'type.required' => 'The setting type is required.',
            'type.in' => 'The setting type must be one of: string, boolean, integer, json.'
        ];
    }
}
