<?php

namespace App\Http\Requests\v1\Supplier\Order;

use App\Enums\OrderStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class S_TakeActionRequest extends FormRequest
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
            'action' => 'required|string|in:accepted,rejected,completed'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
      if (in_array($this->action, ['accept', 'reject'])) {
        $this->merge(['action' => $this->action . 'ed']);
      }
    }
}
