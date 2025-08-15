<?php

namespace App\Http\Requests\v1\Supplier\Product;

use App\Traits\DecodesInputTrait;
use Illuminate\Foundation\Http\FormRequest;

class   UpdatePriceProductRequest extends FormRequest
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
            'product_id' => [
                'required',
                'integer',
                'exists:store_products,id',
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->decodeInput('product_id');
    }


}
