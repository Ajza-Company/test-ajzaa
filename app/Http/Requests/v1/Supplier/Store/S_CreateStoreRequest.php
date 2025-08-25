<?php

namespace App\Http\Requests\v1\Supplier\Store;

use App\Traits\DecodesInputTrait;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class S_CreateStoreRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'data.area_id' => 'required|integer|exists:areas,id',
            'data.image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'data.address' => 'sometimes|string|max:255',
            'data.address_url' => 'sometimes|string|url',
            'data.phone_number' => 'sometimes|string',
            'data.latitude' => 'required|numeric',
            'data.longitude' => 'required|numeric',
            'data.can_add_products' => 'sometimes|boolean',
            'data.car_brand_id' => 'sometimes|array',
            'data.car_brand_id.*' => 'sometimes|integer|exists:car_brands,id',
            'hours' => 'required|array',
            'hours.*.day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'hours.*.open_time' => 'nullable|date_format:H:i',
            'hours.*.close_time' => 'nullable|date_format:H:i|after:hours.*.open_time'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->decodeInput('data.area_id');
        $this->decodeInput('data.category_id');
        $this->decodeSimpleArrayInput('data.car_brand_id.*');
    }
}
