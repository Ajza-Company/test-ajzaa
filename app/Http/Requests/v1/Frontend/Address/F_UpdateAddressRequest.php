<?php

namespace App\Http\Requests\v1\Frontend\Address;

use App\Traits\DecodesInputTrait;
use Illuminate\Foundation\Http\FormRequest;

class F_UpdateAddressRequest extends FormRequest
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
            'name' => 'sometimes|nullable|max:100',
            'house_number' => 'nullable|max:10',
            'level' => 'sometimes|nullable',
            'apartment_number' => 'nullable|max:10',
            'latitude' => 'sometimes|numeric',
            'longitude' => 'sometimes|numeric',
            'is_default' => 'sometimes|boolean',
            'state_id'=>'sometimes|integer|exists:states,id'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->decodeInput('state_id');
    }

}
