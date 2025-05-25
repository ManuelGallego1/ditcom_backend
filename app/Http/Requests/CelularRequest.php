<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CelularRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'marca' => 'required|string|max:50',
            'modelo' => 'required|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'marca.required' => 'La marca es obligatoria.',
            'marca.string' => 'La marca debe ser un texto válido.',
            'marca.max' => 'La marca no debe exceder los 50 caracteres.',

            'modelo.required' => 'El modelo es obligatorio.',
            'modelo.string' => 'El modelo debe ser un texto válido.',
            'modelo.max' => 'El modelo no debe exceder los 50 caracteres.',
        ];
    }
}