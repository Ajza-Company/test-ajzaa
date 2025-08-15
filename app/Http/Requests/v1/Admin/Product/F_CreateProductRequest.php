<?php

namespace App\Http\Requests\v1\Admin\Product;

use App\Traits\DecodesInputTrait;
use Illuminate\Foundation\Http\FormRequest;

class F_CreateProductRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|integer|exists:categories,id',
            'localized' => 'required|array|min:1',
            'localized.*.local_id' => 'required|integer|exists:locales,id',
            'localized.*.name' => 'required|string|max:100',
            'localized.*.description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'part_number' => 'nullable|string|max:50',
            'image' => 'sometimes|nullable|file|max:10240',
            'variate' => 'sometimes|array',
            'variate.*.variant_category_id' => 'required_with:variate|integer|exists:variant_categories,id',
            'variate.*.value' => 'required_with:variate|string|max:100',
            'is_original'=>'required|boolean'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->decodeInput('localized.*.local_id');
        $this->decodeInput('variate.*.variant_category_id');
        $this->decodeInput('category_id');
    }
}
