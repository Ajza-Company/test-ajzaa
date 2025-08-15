<?php

namespace App\Http\Requests\v1\Admin\State;

use App\Traits\DecodesInputTrait;
use App\Enums\EncodingMethodsEnum;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class F_CreateStateRequest extends FormRequest
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
            'country_id'=>'required|integer|exists:countries,id',
            'localized' => 'required|array|min:1',
            'localized.*.local_id' => 'required|integer|exists:locales,id',
            'localized.*.name' => 'required|string|max:100',
            'longitude'=>'nullable|string',
            'latitude'=>'nullable|string'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->decodeInput('localized.*.local_id');
        $this->decodeInput('country_id');
    }

}
