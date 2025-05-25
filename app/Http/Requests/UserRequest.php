<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,administrador,pyme,coordinador,vendedor,rol,activador',
            'activo' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser un texto válido.',
            'name.max' => 'El nombre no debe exceder los 255 caracteres.',

            'username.required' => 'El nombre de usuario es obligatorio.',
            'username.string' => 'El nombre de usuario debe ser un texto válido.',
            'username.max' => 'El nombre de usuario no debe exceder los 255 caracteres.',
            'username.unique' => 'El nombre de usuario ya está en uso.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.string' => 'La contraseña debe ser un texto válido.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',

            'role.required' => 'El rol es obligatorio.',
            'role.string' => 'El rol debe ser un texto válido.',
            'role.in' => 'El rol debe ser uno de los siguientes: admin o user.',

            'activo.boolean' => 'El estado activo debe ser verdadero o falso.',
        ];
    }
}
