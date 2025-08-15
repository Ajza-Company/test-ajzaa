<?php

namespace App\Http\Requests\v1\Frontend\Favorite;

use Illuminate\Foundation\Http\FormRequest;

class F_CreateFavoriteRequest extends FormRequest
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
            'product_id' => ['required', 'integer', 'exists:store_products,id']
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->decodeInput('product_id');
    }

    /**
     * Decode the encoded input value.
     *
     * @param string $inputKey
     */
    private function decodeInput(string $inputKey): void
    {
        $value = $this->input($inputKey);

        if ($value && decodeString($value)) {
            $this->merge([$inputKey => decodeString($value)]);
        }
    }
}
