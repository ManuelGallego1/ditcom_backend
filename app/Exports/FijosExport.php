<?php

namespace App\Exports;

use App\Models\Fijo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class FijosExport implements FromCollection, WithHeadings, WithColumnFormatting, WithColumnWidths, WithStyles
{
    protected $fijos;

    public function __construct($fijos)
    {
        $this->fijos = $fijos;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->fijos->map(function ($fijo) {
            $cliente_nombre = trim(
                $fijo->cliente ? $fijo->cliente->p_nombre .
                ($fijo->cliente->s_nombre ? ' ' . $fijo->cliente->s_nombre : '') . ' ' .
                $fijo->cliente->p_apellido .
                ($fijo->cliente->s_apellido ? ' ' . $fijo->cliente->s_apellido : '') : 'N/A'
            );

            return [
                'fecha_digitacion' => $fijo->created_at
                    ? \PhpOffice\PhpSpreadsheet\Shared\Date::dateTimeToExcel(\Carbon\Carbon::parse($fijo->created_at))
                    : 'N/A',

                'fecha_instalacion' => $fijo->fecha_instalacion
                    ? \PhpOffice\PhpSpreadsheet\Shared\Date::dateTimeToExcel(\Carbon\Carbon::parse($fijo->fecha_instalacion))
                    : 'N/A',

                'fecha_legalizacion' => $fijo->fecha_legalizacion
                    ? \PhpOffice\PhpSpreadsheet\Shared\Date::dateTimeToExcel(\Carbon\Carbon::parse($fijo->fecha_legalizacion))
                    : 'N/A',
                'servicios_adicionales' => $fijo->servicios_adicionales,
                'estrato' => $fijo->estrato,
                'cuenta' => $fijo->cuenta,
                'OT' => $fijo->OT,
                'tipo_producto' => $fijo->tipo_producto,
                'total_servicios' => $fijo->total_servicios,
                'total_adicionales' => $fijo->total_adicionales,
                'cliente_cc' => $fijo->cliente->cc,
                'cliente_nombre' => $cliente_nombre,
                'cliente_numero' => $fijo->cliente->numero,
                'sede' => $fijo->sede->nombre ?? 'N/A',
                'coordinador' => $fijo->sede->coordinador->name ?? 'N/A',
                'vendedor' => $fijo->vendedor->name ?? 'N/A',
                'estado' => $fijo->estado,
                'convergente' => $fijo->convergente,
                'ciudad' => $fijo->ciudad,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Fecha Digitación',
            'Fecha Instalación',
            'Fecha Legalización',
            'Servicios + Adicionales',
            'Estrato',
            'Cuenta',
            'OT',
            'Tipo Producto',
            'Total Servicios',
            'Total Adicionales',
            'Cliente CC',
            'Cliente Nombre',
            'Cliente Número',
            'Sede',
            'Coordinador',
            'Vendedor',
            'Estado',
            'Convergente',
            'Ciudad',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_DMYSLASH, // Fecha Digitación
            'B' => NumberFormat::FORMAT_DATE_DMYSLASH, // Fecha Instalación
            'C' => NumberFormat::FORMAT_DATE_DMYSLASH, // Fecha Legalización
        ];
    }

    /**
     * Define los anchos de las columnas.
     *
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 25,
            'D' => 25,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 20,
            'I' => 20,
            'J' => 20,
            'K' => 30,
            'L' => 20,
            'M' => 20,
            'N' => 15,
            'O' => 20,
            'P' => 20,
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
