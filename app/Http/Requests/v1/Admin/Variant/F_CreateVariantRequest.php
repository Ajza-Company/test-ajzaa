<?php

namespace App\Http\Requests\v1\Admin\Variant;

use App\Traits\DecodesInputTrait;
use App\Enums\EncodingMethodsEnum;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class F_CreateVariantRequest extends FormRequest
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
            'category_id'=>'required|integer|exists:categories,id',
            'localized'=>'required|array|min:1',
            'localized.*.local_id'=>'required|integer|exists:locales,id',
            'localized.*.name'=>'required|string|max:100',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->decodeInput('localized.*.local_id');
        $this->decodeInput('category_id');
    }

}
