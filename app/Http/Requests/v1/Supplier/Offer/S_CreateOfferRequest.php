<?php

namespace App\Http\Requests\v1\Supplier\Offer;

use App\Traits\DecodesInputTrait;
use Illuminate\Foundation\Http\FormRequest;

class S_CreateOfferRequest extends FormRequest
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
            'product_id' => 'required|integer|exists:store_products,id',
            'type' => 'required|string|in:fixed,percentage',
            'discount' => 'required|numeric',
            'expires_at' => 'nullable|date|after:now',
            'is_ajza_offer'=>'nullable|boolean'
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
