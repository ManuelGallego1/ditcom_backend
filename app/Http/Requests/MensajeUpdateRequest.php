<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MensajeUpdateRequest extends FormRequest
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
            'grupo_id' => 'nullable|exists:grupos,id',
            'user_id' => 'nullable|exists:users,id',
            'contenido' => 'nullable|string|max:1000',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,ppt,pptx,mp3,wav|max:5120',
            'file_path' => 'nullable|string|max:255',
            'leido' => 'nullable|boolean',
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
            'grupo_id.exists' => 'El grupo seleccionado no existe en el sistema.',

            'user_id.exists' => 'El usuario seleccionado no existe en el sistema.',

            'contenido.string' => 'El contenido del mensaje debe ser un texto válido.',
            'contenido.max' => 'El contenido del mensaje no debe exceder los 1000 caracteres.',

            'file_path.string' => 'La ruta del archivo debe ser un texto válido.',
            'file_path.max' => 'La ruta del archivo no debe exceder los 255 caracteres.',

            'leido.boolean' => 'El campo "leído" debe ser verdadero o falso.',
            
            'file.file' => 'El archivo debe ser un archivo válido.',
            'file.mimes' => 'El archivo debe ser de tipo: jpg, jpeg, png, pdf, doc, docx, xls, xlsx, ppt, pptx, mp3 o wav.',
            'file.max' => 'El archivo no debe exceder los 5MB.',

        ];
    }
}