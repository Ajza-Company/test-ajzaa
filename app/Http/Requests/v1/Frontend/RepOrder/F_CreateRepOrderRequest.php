<?php

namespace App\Http\Requests\v1\Frontend\RepOrder;

use App\Traits\DecodesInputTrait;
use Illuminate\Foundation\Http\FormRequest;

class F_CreateRepOrderRequest extends FormRequest
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
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'data.title' => 'required|string',
            'data.description' => 'required|string',
            'data.city_id' => 'sometimes|integer|exists:states,id',
            'data.address_id' => 'sometimes|integer|exists:addresses,id',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->decodeInput('data.city_id');
        $this->decodeInput('data.address_id');
    }

    /**
     * Get the validated data from the request.
     *
     * @param null $key
     * @param null $default
     * @return array
     */
    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated();

        if (isset($validated['data']['city_id'])) {
            $validated['data']['state_id'] = $validated['data']['city_id'];
            unset($validated['data']['city_id']);
        }

        return $validated;
    }
}
