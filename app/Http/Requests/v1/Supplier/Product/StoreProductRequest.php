<?php

namespace App\Http\Requests\v1\Supplier\Product;

use App\Traits\DecodesInputTrait;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'is_select_all' => 'required|boolean',
    
            'product_ids' => [
                'nullable',
                'required_if:is_select_all,false',
                'array'
            ],

            'product_ids.*' => [
                'required_with:product_ids',
                'integer'
            ],

        ];
    }
            
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->decodeInput('product_ids.*');
    }

    
}
