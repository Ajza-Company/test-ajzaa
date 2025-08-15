<?php

namespace App\Http\Requests\v1\General\RepChat;

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
            'message' => 'required_without:attachment|nullable|string',
            'attachment' => 'required_without:message|nullable|file|max:10240',
            'message_type' => 'sometimes|in:text,offer,attachment,invoice,start_delivery',
            'is_invoice' => 'sometimes|boolean'
        ];
    }
}
