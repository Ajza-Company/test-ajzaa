<?php

namespace App\Http\Requests\v1\Admin\User;

use App\Traits\DecodesInputTrait;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class A_notificationUserRequest extends FormRequest
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
            'title' => 'required|string',
            'message' => 'required|string',
            'is_select_all'=>'sometimes|boolean',
            'users' => 'sometimes|array|min:1',
            'users.*' => [
                'required_with:users',
                'integer',
                Rule::exists('users', 'id'),
            ]
        ];
    }

    protected function prepareForValidation(): void
    {
        if (isset($this->users)) {
            $this->decodeInput('users.*');
        }
    }
}
