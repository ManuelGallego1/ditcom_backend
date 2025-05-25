<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovilRequest extends FormRequest
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
            'min' => 'required|string|size:10',
            'imei' => 'required|string|size:15',
            'iccid' => 'required|string|size:17',
            'tipo' => 'required|in:kit prepago,kit financiado,wb,up grade,linea nueva,reposicion,portabilidad pre,portabilidad pos,venta de tecnologia,equipo pos',
            'plan_id' => 'required|exists:planes,id',
            'celulares_id' => 'required|exists:celulares,id',
            'cliente_cc' => 'required|exists:clientes,cc',
            'tipo_producto' => 'required|in:residencial,pyme',
            'factura' => 'required|string',
            'ingreso_caja' => 'required|string',
            'valor_recarga' => 'nullable',
            'valor_total' => 'required|numeric',
            'vendedor_id' => 'required|exists:users,id',
            'financiera' => 'required|in:crediminuto,celya,brilla,N/A',
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
            'min.required' => 'El número MIN es obligatorio.',
            'min.string' => 'El número MIN debe ser un texto válido.',
            'min.size' => 'El número MIN debe tener exactamente 10 caracteres.',

            'imei.required' => 'El IMEI es obligatorio.',
            'imei.string' => 'El IMEI debe ser un texto válido.',
            'imei.size' => 'El IMEI debe tener exactamente 15 caracteres.',

            'iccid.required' => 'El ICCID es obligatorio.',
            'iccid.string' => 'El ICCID debe ser un texto válido.',
            'iccid.size' => 'El ICCID debe tener exactamente 17 caracteres.',

            'tipo.required' => 'El tipo es obligatorio.',
            'tipo.in' => 'El tipo debe ser uno de los siguientes valores: kit prepago, kit financiado, wb, up grade, línea nueva, reposición, portabilidad pre, portabilidad pos, venta de tecnología, equipo pos.',

            'plan_id.required' => 'El plan es obligatorio.',
            'plan_id.exists' => 'El plan seleccionado no existe en el sistema.',

            'celulares_id.required' => 'El celular es obligatorio.',
            'celulares_id.exists' => 'El celular seleccionado no existe en el sistema.',

            'cliente_cc.required' => 'El cliente (cédula) es obligatorio.',
            'cliente_cc.exists' => 'El cliente con esta cédula no existe en el sistema.',

            'tipo_producto.required' => 'El tipo de producto es obligatorio.',
            'tipo_producto.in' => 'El tipo de producto debe ser residencial o pyme.',

            'factura.required' => 'La factura es obligatoria.',
            'factura.string' => 'La factura debe ser un texto válido.',

            'ingreso_caja.required' => 'El ingreso a caja es obligatorio.',
            'ingreso_caja.string' => 'El ingreso a caja debe ser un texto válido.',

            'valor_total.required' => 'El valor total es obligatorio.',
            'valor_total.numeric' => 'El valor total debe ser un número válido.',

            'vendedor_id.required' => 'El vendedor es obligatorio.',
            'vendedor_id.exists' => 'El vendedor seleccionado no existe en el sistema.',

            'financiera.required' => 'La financiera es obligatoria.',
            'financiera.in' => 'La financiera debe ser uno de los siguientes valores: crediminuto, celya, brilla, N/A.',
        ];
    }
}