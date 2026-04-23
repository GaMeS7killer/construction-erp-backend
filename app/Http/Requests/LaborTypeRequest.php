<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LaborTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'pay_type' => ['required', Rule::in(['daily', 'monthly', 'fixed'])],
            'rate' => ['required', 'numeric', 'min:0'],
        ];
    }
}
