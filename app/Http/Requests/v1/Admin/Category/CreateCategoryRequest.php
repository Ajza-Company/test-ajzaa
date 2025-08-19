<?php

namespace App\Http\Requests\v1\Admin\Category;

use App\Traits\DecodesInputTrait;
use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'localized' => 'required|array|min:1',
            'localized.*.local_id' => 'required|integer|exists:locales,id',
            'localized.*.name' => 'required|string|max:100',
        ];
    }

        /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->decodeInput('localized.*.local_id');
    }

}
