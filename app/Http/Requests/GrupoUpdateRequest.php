<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GrupoUpdateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'nullable|string|max:50',
            'es_privado' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.string' => 'El nombre del grupo debe ser un texto válido.',
            'nombre.max' => 'El nombre del grupo no debe exceder los 50 caracteres.',

            'es_privado.boolean' => 'El campo "es privado" debe ser verdadero o falso.',
        ];
    }
}