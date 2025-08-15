<?php

namespace App\Http\Requests\v1\General\RepChat;

use App\Traits\DecodesInputTrait;
use Illuminate\Foundation\Http\FormRequest;

class G_UpdateOfferRequest extends FormRequest
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
            'status' => 'required|in:accepted,rejected',
            'chat_id' => 'required|exists:rep_chats,id'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->decodeInput('chat_id');
    }
}
