<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GrupoRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:50',
            'es_privado' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del grupo es obligatorio.',
            'nombre.string' => 'El nombre del grupo debe ser un texto válido.',
            'nombre.max' => 'El nombre del grupo no debe exceder los 50 caracteres.',

            'es_privado.required' => 'El campo "es privado" es obligatorio.',
            'es_privado.boolean' => 'El campo "es privado" debe ser verdadero o falso.',
        ];
    }
}