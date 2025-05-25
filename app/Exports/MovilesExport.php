<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MovilesExport implements FromCollection, WithHeadings, WithColumnFormatting, WithColumnWidths
{
    protected $moviles;

    public function __construct($moviles)
    {
        $this->moviles = $moviles;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->moviles->map(function ($movil) {
            $cliente_nombre = trim(
                $movil->cliente ? $movil->cliente->p_nombre .
                ($movil->cliente->s_nombre ? ' ' . $movil->cliente->s_nombre : '') . ' ' .
                $movil->cliente->p_apellido .
                ($movil->cliente->s_apellido ? ' ' . $movil->cliente->s_apellido : '') : 'N/A'
            );
            $celular_marca = $movil->celular ? $movil->celular->marca : 'N/A';
            $celular_modelo = $movil->celular ? $movil->celular->modelo : 'N/A';
            $plan_codigo_nombre = $movil->plan ? $movil->plan->codigo . ' - ' . $movil->plan->nombre : 'N/A';

            return [
                'fecha_create' => $movil->created_at,
                'fecha_modify' => $movil->updated_at,
                'min' => $movil->min,
                'imei' => $movil->imei,
                'iccid' => $movil->iccid . " ",
                'tipo' => $movil->tipo,
                'plan' => $plan_codigo_nombre,
                'celular_marca' => $celular_marca,
                'celular_modelo' => $celular_modelo,
                'cliente_cc' => $movil->cliente->cc ?? 'N/A',
                'cliente_nombre' => $cliente_nombre,
                'cliente_correo' => $movil->cliente->email ?? 'N/A',
                'cliente_numero' => $movil->cliente->numero ?? 'N/A',
                'factura' => $movil->factura,
                'ingreso_caja' => $movil->ingreso_caja,
                'valor_total' => $movil->valor_total,
                'vendedor' => $movil->vendedor->name ?? 'N/A',
                'sede' => $movil->sede->nombre ?? 'N/A',
                'financiera' => $movil->financiera,
                'coordinador' => $movil->coordinador->name ?? 'N/A',
                'valor_recarga' => $movil->valor_recarga,
                'tipo_producto' => $movil->tipo_producto,
            ];
        })->map(function ($row) {
            $row['min'] = (string) $row['min'];
            $row['imei'] = (string) $row['imei'];
            $row['iccid'] = (string) $row['iccid'];
            return $row;
        });
    }

    /**
     * Define los encabezados del archivo Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Fecha Creación',
            'Fecha Modificación',
            'Min',
            'IMEI',
            'ICCID',
            'Tipo',
            'Plan',
            'Marca del Celular',
            'Modelo del Celular',
            'Cédula del Cliente',
            'Nombre del Cliente',
            'Correo del Cliente',
            'Número del Cliente',
            'Factura',
            'Ingreso Caja',
            'Valor Total',
            'Vendedor',
            'Sede',
            'Financiera',
            'Coordinador',
            'Valor Recarga',
            'Tipo de producto'
        ];
    }

    /**
     * Formato de las columnas.
     *
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
            'C' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'E' => NumberFormat::FORMAT_TEXT,
            'O' => '#,##0.00',
            'T' => '#,##0.00'
        ];
    }

    /**
     * Anchos de las columnas.
     *
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 15, // Fecha
            'B' => 20, // Min
            'C' => 20, // IMEI
            'D' => 20, // ICCID
            'E' => 15, // Tipo
            'F' => 25, // Plan
            'G' => 20, // Marca del Celular
            'H' => 20, // Modelo del Celular
            'I' => 20, // Cédula del Cliente
            'J' => 30, // Nombre del Cliente
            'K' => 30, // Correo del Cliente
            'L' => 20, // Número del Cliente
            'M' => 15, // Factura
            'N' => 15, // Ingreso Caja
            'O' => 15, // Valor Total
            'P' => 20, // Vendedor
            'Q' => 20, // Sede
            'R' => 20, // Financiera
            'S' => 20, // Coordinador
            'T' => 15, // Valor Recarga
            'U' => 20, // Tipo de producto
        ];
    }

    /**
     * Estilos de las celdas.
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Encabezados en negrita
        ];
    }
}