<?php

namespace App\Http\Requests\v1\Admin\Setting;

use Illuminate\Foundation\Http\FormRequest;

class A_CreateSettingRequest extends FormRequest
{

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
            'setting.order_percentage' => 'required|numeric|min:0',
            'setting.rep_order_percentage'=> 'required|numeric|min:0',
            'setting.km_initial_cost_rep_order'=> 'required|numeric|min:0',
            'setting.delivery_initial_cost_rep_order'=> 'required|numeric|min:0',
            'setting.max_delivery_cost_rep_order'=> 'required|numeric|min:0',

        ];
    }

}
