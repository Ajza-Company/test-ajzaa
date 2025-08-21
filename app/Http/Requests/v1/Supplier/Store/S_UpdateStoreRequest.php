<?php

namespace App\Http\Requests\v1\Supplier\Store;

use App\Traits\DecodesInputTrait;
use Illuminate\Foundation\Http\FormRequest;

class S_UpdateStoreRequest extends FormRequest
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
            'data.area_id' => 'sometimes|integer|exists:areas,id',
            'data.image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'data.address' => 'sometimes|string|max:255',
            'data.latitude' => 'required|numeric',
            'data.longitude' => 'required|numeric',
//            'data.category_id' => 'sometimes|integer|exists:categories,id',
            'data.address_url' => 'sometimes|string|url',
            'data.phone_number' => 'sometimes|string',
            'data.is_active' => 'sometimes|boolean',
            'data.can_add_products' => 'sometimes|boolean',
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
    }
}
