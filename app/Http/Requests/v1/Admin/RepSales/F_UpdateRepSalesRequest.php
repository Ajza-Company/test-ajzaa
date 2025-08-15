<?php

namespace App\Http\Requests\v1\Admin\RepSales;

use Illuminate\Validation\Rule;
use App\Traits\DecodesInputTrait;
use Illuminate\Foundation\Http\FormRequest;

class F_UpdateRepSalesRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->id),
            ],
            'full_mobile' => [
                'sometimes',
                'string',
                Rule::unique('users', 'full_mobile')->ignore($this->id),
            ],
            'password' => 'sometimes|min:8',
            'avatar' => 'sometimes|nullable|file|max:2408',
            'city_id' => 'required|integer|exists:states,id',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->decodeInput('city_id');
        $this->merge([
            'id' => decodeString($this->route('id')), // Decode the ID and merge it back into the request
        ]);
    }
}
