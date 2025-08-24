<?php

namespace App\Http\Requests\v1\Admin\Store;

use App\Traits\DecodesInputTrait;
use Illuminate\Foundation\Http\FormRequest;

class A_UpdateStoreRequest extends FormRequest
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
    public function rules()
    {
        return [
            'company_id' => 'sometimes|exists:companies,id',
            'area_id' => 'sometimes|exists:areas,id',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'address' => 'sometimes|string|max:255',
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
            'address_url' => 'sometimes|string|url|nullable',
            'phone_number' => 'sometimes|string|max:20',
            'is_active' => 'sometimes|boolean',
            'can_add_products' => 'sometimes|boolean',
            'is_approved' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer|min:0',
            'category_id' => 'sometimes|exists:categories,id',
            'hours' => 'sometimes|array',
            'hours.*.day' => 'required_with:hours|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'hours.*.open_time' => 'nullable|date_format:H:i',
            'hours.*.close_time' => 'nullable|date_format:H:i|after:hours.*.open_time'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'company_id.exists' => 'الشركة المحددة غير موجودة',
            'area_id.exists' => 'المنطقة المحددة غير موجودة',
            'category_id.exists' => 'الفئة المحددة غير موجودة',
            'latitude.between' => 'خط العرض يجب أن يكون بين -90 و 90',
            'longitude.between' => 'خط الطول يجب أن يكون بين -180 و 180',
            'hours.*.day.in' => 'يوم الأسبوع غير صحيح',
            'hours.*.close_time.after' => 'وقت الإغلاق يجب أن يكون بعد وقت الفتح'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->decodeInput('company_id');
        $this->decodeInput('area_id');
        $this->decodeInput('category_id');
    }
}
