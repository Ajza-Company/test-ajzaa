<?php

namespace App\Http\Requests\v1\Frontend\Auth;

use App\Enums\EncodingMethodsEnum;
use App\Traits\DecodesInputTrait;
use Illuminate\Foundation\Http\FormRequest;

class F_SetupAccountRequest extends FormRequest
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
            'personal.car_brand_id' => 'required|integer|exists:car_brands,id',
            'personal.car_model_id' => 'required|integer|exists:car_models,id',
            'personal.car_year' => 'required|integer|between:1900,' . date('Y'),
            'personal.car_type_id' => 'required|integer|exists:car_types,id',
            'personal.vin' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->decodeInput('personal.car_brand_id');
        $this->decodeInput('personal.car_model_id');
        $this->decodeInput('personal.car_type_id');
    }
}
