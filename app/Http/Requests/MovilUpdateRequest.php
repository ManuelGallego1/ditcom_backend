<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovilUpdateRequest extends FormRequest
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
            'min' => 'nullable|string|size:10',
            'imei' => 'nullable|string|size:15',
            'iccid' => 'nullable|string|size:17',
            'tipo' => 'nullable|in:kit prepago,kit financiado,wb,up grade,linea nueva,reposicion,portabilidad pre,portabilidad pos,venta de tecnologia,equipo pos',
            'plan_id' => 'nullable|exists:planes,id',
            'celulares_id' => 'nullable|exists:celulares,id',
            'cliente_cc' => 'nullable|exists:clientes,cc',
            'tipo_producto' => 'nullable|in:residencial,pyme',
            'factura' => 'nullable|string',
            'ingreso_caja' => 'nullable|string',
            'valor_recarga' => 'nullable',
            'valor_total' => 'nullable|numeric',
            'vendedor_id' => 'nullable|exists:users,id',
            'financiera' => 'nullable|in:crediminuto,celya,brilla,N/A',
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
            'min.string' => 'El número MIN debe ser un texto válido.',
            'min.size' => 'El número MIN debe tener exactamente 10 caracteres.',

            'imei.string' => 'El IMEI debe ser un texto válido.',
            'imei.size' => 'El IMEI debe tener exactamente 15 caracteres.',

            'iccid.string' => 'El ICCID debe ser un texto válido.',
            'iccid.size' => 'El ICCID debe tener exactamente 17 caracteres.',

            'tipo.in' => 'El tipo debe ser uno de los siguientes valores: kit prepago, kit financiado, wb, up grade, línea nueva, reposición, portabilidad pre, portabilidad pos, venta de tecnología, equipo pos.',

            'plan_id.exists' => 'El plan seleccionado no existe en el sistema.',

            'celulares_id.exists' => 'El celular seleccionado no existe en el sistema.',

            'cliente_cc.exists' => 'El cliente con esta cédula no existe en el sistema.',

            'tipo_producto.in' => 'El tipo de producto debe ser residencial o pyme.',

            'factura.string' => 'La factura debe ser un texto válido.',

            'ingreso_caja.string' => 'El ingreso a caja debe ser un texto válido.',

            'valor_total.numeric' => 'El valor total debe ser un número válido.',

            'vendedor_id.exists' => 'El vendedor seleccionado no existe en el sistema.',

            'financiera.in' => 'La financiera debe ser uno de los siguientes valores: crediminuto, celya, brilla, N/A.',
        ];
    }
}