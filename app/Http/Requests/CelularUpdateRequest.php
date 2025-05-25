<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CelularUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'marca' => 'nullable|string|max:50',
            'modelo' => 'nullable|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'marca.string' => 'La marca debe ser un texto válido.',
            'marca.max' => 'La marca no debe exceder los 50 caracteres.',

            'modelo.string' => 'El modelo debe ser un texto válido.',
            'modelo.max' => 'El modelo no debe exceder los 50 caracteres.',
        ];
    }
}