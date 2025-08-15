<?php

namespace App\Http\Requests\v1\General\SupportChat;

use Illuminate\Foundation\Http\FormRequest;

class G_SendMessageRequest extends FormRequest
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
            'message' => 'required_without:attachment|string|nullable',
            'attachment' => 'required_without:message|file|max:10240|nullable'
        ];
    }
}