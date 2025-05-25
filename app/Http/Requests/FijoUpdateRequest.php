<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FijoUpdateRequest extends FormRequest
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
            'fecha_instalacion' => 'nullable|date',
            'fecha_legalizacion' => 'nullable|date',
            'servicios_adicionales' => 'nullable|string',
            'estrato' => 'nullable|in:1,2,3,4,5,6,NR',
            'cuenta' => 'nullable|integer',
            'OT' => 'nullable|integer',
            'tipo_producto' => 'nullable|in:residencial,pyme',
            'total_servicios' => 'nullable|in:0,1,2,3',
            'total_adicionales' => 'nullable|in:0,1,2,3',
            'cliente_cc' => 'nullable|string|exists:clientes,cc',
            'convergente' => 'nullable|string',
            'ciudad' => 'nullable|string',
            'vendedor_id' => 'nullable|exists:users,id',
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
            'fecha_instalacion.date' => 'La fecha de instalación debe ser una fecha válida.',
            'fecha_legalizacion.date' => 'La fecha de legalización debe ser una fecha válida.',

            'servicios_adicionales.string' => 'Los servicios adicionales deben ser un texto válido.',

            'estrato.in' => 'El estrato debe ser uno de los siguientes valores: 1, 2, 3, 4, 5, 6 o NR.',

            'cuenta.integer' => 'La cuenta debe ser un número entero.',

            'OT.integer' => 'La OT debe ser un número entero.',

            'tipo_producto.in' => 'El tipo de producto debe ser residencial o pyme.',

            'total_servicios.in' => 'El total de servicios debe ser uno de los siguientes valores: 0, 1, 2 o 3.',
            'total_adicionales.in' => 'El total de adicionales debe ser uno de los siguientes valores: 0, 1, 2 o 3.',

            'cliente_cc.string' => 'El cliente (cédula) debe ser un texto válido.',
            'cliente_cc.exists' => 'El cliente con esta cédula no existe en el sistema.',

            'convergente.string' => 'El campo convergente debe ser un texto válido.',

            'ciudad.string' => 'La ciudad debe ser un texto válido.',

            'vendedor_id.exists' => 'El vendedor seleccionado no existe en el sistema.',
        ];
    }
}