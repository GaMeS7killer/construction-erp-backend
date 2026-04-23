<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['house', 'workshop', 'building', 'other'])],
            'area_m2' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', Rule::in(['planning', 'in_progress', 'completed', 'cancelled'])],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.required' => 'العميل مطلوب.',
            'client_id.exists' => 'العميل المحدد غير موجود.',
            'name.required' => 'اسم المشروع مطلوب.',
            'type.required' => 'نوع المشروع مطلوب.',
            'end_date.after_or_equal' => 'تاريخ الانتهاء يجب أن يكون بعد أو مساوياً لتاريخ البداية.',
        ];
    }
}
