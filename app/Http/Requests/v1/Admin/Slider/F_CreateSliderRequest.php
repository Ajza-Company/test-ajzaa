<?php

namespace App\Http\Requests\v1\Admin\Slider;

use App\Traits\DecodesInputTrait;
use App\Enums\EncodingMethodsEnum;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class F_CreateSliderRequest extends FormRequest
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
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:10240',
            'order' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
            'locale_id' => 'required|exists:locales,id'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'image.required' => 'The image is required.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, webp.',
            'image.max' => 'The image may not be greater than 10MB.',
            'order.integer' => 'The order must be an integer.',
            'order.min' => 'The order must be at least 1.',
            'locale_id.required' => 'The locale is required.',
            'locale_id.exists' => 'The selected locale is invalid.'
        ];
    }
}
