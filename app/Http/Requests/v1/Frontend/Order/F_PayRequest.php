<?php

namespace App\Http\Requests\v1\Frontend\Order;

use App\Enums\PaymentMethodsEnum;
use App\Traits\DecodesInputTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class F_PayRequest extends FormRequest
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
            'payment_method' => ['required', 'string', Rule::in(PaymentMethodsEnum::asArray())],
            'amount'=>'required|numeric|min:0'
        ];
    }
}
