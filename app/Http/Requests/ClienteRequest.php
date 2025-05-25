<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
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
            'p_nombre' => 'required|string|max:50',
            's_nombre' => 'nullable|string|max:50',
            'p_apellido' => 'required|string|max:50',
            's_apellido' => 'nullable|string|max:50',
            'email' => 'required|email|max:100',
            'numero' => 'required|string|max:20',
            'cc' => 'required|string|max:20|unique:clientes,cc',
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
            'p_nombre.required' => 'El primer nombre es obligatorio.',
            'p_nombre.string' => 'El primer nombre debe ser un texto válido.',
            'p_nombre.max' => 'El primer nombre no debe exceder los 50 caracteres.',

            's_nombre.string' => 'El segundo nombre debe ser un texto válido.',
            's_nombre.max' => 'El segundo nombre no debe exceder los 50 caracteres.',

            'p_apellido.required' => 'El primer apellido es obligatorio.',
            'p_apellido.string' => 'El primer apellido debe ser un texto válido.',
            'p_apellido.max' => 'El primer apellido no debe exceder los 50 caracteres.',

            's_apellido.string' => 'El segundo apellido debe ser un texto válido.',
            's_apellido.max' => 'El segundo apellido no debe exceder los 50 caracteres.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
            'email.max' => 'El correo electrónico no debe exceder los 100 caracteres.',

            'numero.required' => 'El número de contacto es obligatorio.',
            'numero.string' => 'El número de contacto debe ser un texto válido.',
            'numero.max' => 'El número de contacto no debe exceder los 20 caracteres.',

            'cc.required' => 'La cédula es obligatoria.',
            'cc.string' => 'La cédula debe ser un texto válido.',
            'cc.max' => 'La cédula no debe exceder los 20 caracteres.',
            'cc.unique' => 'La cédula ya está registrada.',
        ];
    }
}