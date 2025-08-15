<?php

namespace App\Http\Requests\v1\Frontend\Cart;

use App\Enums\OrderDeliveryMethodEnum;
use App\Traits\DecodesInputTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CartRequest extends FormRequest
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
            'delivery_method' => ['required', 'string', Rule::in(OrderDeliveryMethodEnum::asArray())],
            // 'address_id' => 'required_if:delivery_method,delivery|integer|exists:addresses,id',
            'order_products' => 'required|array',
            'order_products.*.product_id' => 'required|integer|exists:store_products,id',
            'order_products.*.quantity' => 'required|integer|min:1',
            'promo_code'=>'sometimes|exists:promo_codes,code'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // $this->decodeInput('address_id');
        $this->decodeInput('order_products.*.product_id');
    }
}
