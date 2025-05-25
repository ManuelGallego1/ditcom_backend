<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SedeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Cambiado a true para permitir el uso del request
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:100',
            'coordinador_id' => 'required|exists:users,id',
        ];
    }

    /**
     * Get the custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre de la sede es obligatorio.',
            'nombre.string' => 'El nombre de la sede debe ser un texto válido.',
            'nombre.max' => 'El nombre de la sede no debe exceder los 100 caracteres.',

            'coordinador_id.required' => 'El coordinador es obligatorio.',
            'coordinador_id.exists' => 'El coordinador seleccionado no existe en el sistema.',
        ];
    }
}