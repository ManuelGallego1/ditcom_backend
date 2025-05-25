<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'codigo' => 'nullable|string|max:50|unique:planes,codigo',
            'nombre' => 'nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'codigo.string' => 'El código debe ser un texto válido.',
            'codigo.max' => 'El código no debe exceder los 50 caracteres.',
            'codigo.unique' => 'Este código ya está en uso.',

            'nombre.string' => 'El nombre debe ser un texto válido.',
            'nombre.max' => 'El nombre no debe exceder los 100 caracteres.',
        ];
    }
}
