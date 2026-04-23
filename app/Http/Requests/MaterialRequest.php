<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'unit' => ['required', Rule::in(['m2', 'm3', 'ton', 'piece', 'liter', 'kg', 'meter'])],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'category' => ['required', Rule::in(['concrete', 'steel', 'block', 'paint', 'tile', 'insulation', 'other'])],
            'description' => ['nullable', 'string'],
        ];
    }
}
