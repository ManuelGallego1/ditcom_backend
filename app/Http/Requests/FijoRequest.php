<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FijoRequest extends FormRequest
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
            'servicios_adicionales' => 'required|string',
            'estrato' => 'required|in:1,2,3,4,5,6,NR',
            'cuenta' => 'required|integer',
            'OT' => 'required|integer',
            'tipo_producto' => 'required|in:residencial,pyme',
            'total_servicios' => 'nullable|in:0,1,2,3',
            'total_adicionales' => 'nullable|in:0,1,2,3',
            'cliente_cc' => 'required|string|exists:clientes,cc',
            'convergente' => 'required|string',
            'ciudad' => 'required|string',
            'vendedor_id' => 'required|exists:users,id',
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

            'servicios_adicionales.required' => 'Los servicios adicionales son obligatorios.',
            'servicios_adicionales.string' => 'Los servicios adicionales deben ser un texto válido.',

            'estrato.required' => 'El estrato es obligatorio.',
            'estrato.in' => 'El estrato debe ser uno de los siguientes valores: 1, 2, 3, 4, 5, 6 o NR.',

            'cuenta.required' => 'La cuenta es obligatoria.',
            'cuenta.integer' => 'La cuenta debe ser un número entero.',

            'OT.required' => 'La OT es obligatoria.',
            'OT.integer' => 'La OT debe ser un número entero.',

            'tipo_producto.required' => 'El tipo de producto es obligatorio.',
            'tipo_producto.in' => 'El tipo de producto debe ser residencial o pyme.',

            'total_servicios.in' => 'El total de servicios debe ser uno de los siguientes valores: 0, 1, 2 o 3.',
            'total_adicionales.in' => 'El total de adicionales debe ser uno de los siguientes valores: 0, 1, 2 o 3.',

            'cliente_cc.required' => 'El cliente (cédula) es obligatorio.',
            'cliente_cc.string' => 'El cliente (cédula) debe ser un texto válido.',
            'cliente_cc.exists' => 'El cliente con esta cédula no existe en el sistema.',

            'convergente.required' => 'El campo convergente es obligatorio.',
            'convergente.string' => 'El campo convergente debe ser un texto válido.',

            'ciudad.required' => 'La ciudad es obligatoria.',
            'ciudad.string' => 'La ciudad debe ser un texto válido.',

            'vendedor_id.required' => 'El vendedor es obligatorio.',
            'vendedor_id.exists' => 'El vendedor seleccionado no existe en el sistema.',
        ];
    }
}