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
            'image'=>'required|image|mimes:jpeg,png,jpg|max:10240',
        ];
    }
}
