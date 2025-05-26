<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Fijo;
use App\Models\Movil;
use App\Models\Cliente;
use App\Models\Celular;
use Carbon\Carbon;

class EstadisticasController extends Controller
{
    public function getStatistics()
    {
        $fijos = Fijo::count();
        $moviles = Movil::count();
        $clientes = Cliente::count();
        $celulares = Celular::count();

        return response()->json([
            'fijos' => $fijos,
            'moviles' => $moviles,
            'clientes' => $clientes,
            'celulares' => $celulares,
        ]);
    }

    public function getFijosStatistics(Request $request)
    {
        $query = Fijo::query();

        if ($request->filled('vendedor_id')) {
            $query->where('vendedor_id', $request->vendedor_id);
        }

        if ($request->filled('sede_id')) {
            $query->where('sede_id', $request->sede_id);
        }

        if ($request->boolean('ventas_pyme')) {
            $query->where('tipo_producto', 'pyme');
        }

        if ($request->filled('coordinador_id')) {
            $query->whereHas('sede', function ($q) use ($request) {
                $q->where('coordinador_id', $request->coordinador_id);
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            $query->whereBetween('fecha_instalacion', [$startDate, $endDate]);
        }

        $baseQuery = clone $query;

        $stats = [
            'total' => $baseQuery->count(),
            'pyme' => (clone $baseQuery)->where('tipo_producto', 'pyme')->count(),
            'residencial' => (clone $baseQuery)->where('tipo_producto', 'residencial')->count(),
        ];

        return response()->json($stats, 200);
    }

    public function getMovilesStatistics(Request $request)
    {
        $query = Movil::query();

        if ($request->filled('vendedor_id')) {
            $query->where('vendedor_id', $request->vendedor_id);
        }

        if ($request->filled('sede_id')) {
            $query->where('sede_id', $request->sede_id);
        }

        if ($request->boolean('ventas_pyme')) {
            $query->where('tipo_producto', 'pyme');
        }

        if ($request->filled('coordinador_id')) {
            $query->whereHas('sede', function ($q) use ($request) {
                $q->where('coordinador_id', $request->coordinador_id);
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        }

        $baseQuery = clone $query;

        $stats = [
            'total' => $baseQuery->count(),
            'pyme' => (clone $baseQuery)->where('tipo_producto', 'pyme')->count(),
            'residencial' => (clone $baseQuery)->where('tipo_producto', 'residencial')->count(),
        ];

        return response()->json($stats, 200);
    }

    public function VentasPorMesAnioActual()
    {
        $year = Carbon::now()->year;
        $ventasFijos = [];
        $ventasMoviles = [];

        for ($month = 1; $month <= 12; $month++) {
            $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::create($year, $month, 1)->endOfMonth();

            $ventasFijos[] = Fijo::whereBetween('fecha_instalacion', [$startOfMonth, $endOfMonth])->count();
            $ventasMoviles[] = Movil::whereBetween('updated_at', [$startOfMonth, $endOfMonth])->count();
        }

        return response()->json([
            'fijos' => $ventasFijos,
            'moviles' => $ventasMoviles,
        ]);
    }

    public function MejorVendedorGeneral()
    {
        $ventasFijo = Fijo::select('vendedor_id', \DB::raw('count(*) as total'))
            ->groupBy('vendedor_id')
            ->get()
            ->keyBy('vendedor_id');

        $ventasMovil = Movil::select('vendedor_id', \DB::raw('count(*) as total'))
            ->groupBy('vendedor_id')
            ->get()
            ->keyBy('vendedor_id');

        $totales = [];
        foreach ($ventasFijo as $vendedor_id => $fijo) {
            $totales[$vendedor_id] = $fijo->total + ($ventasMovil[$vendedor_id]->total ?? 0);
        }
        foreach ($ventasMovil as $vendedor_id => $movil) {
            if (!isset($totales[$vendedor_id])) {
                $totales[$vendedor_id] = $movil->total;
            }
        }
        
        $mejorVendedorId = null;
        $maxVentas = 0;
        foreach ($totales as $vendedor_id => $total) {
            if ($total > $maxVentas) {
                $maxVentas = $total;
                $mejorVendedorId = $vendedor_id;
            }
        }

        $ventasFijoMejor = $ventasFijo[$mejorVendedorId]->total ?? 0;
        $ventasMovilMejor = $ventasMovil[$mejorVendedorId]->total ?? 0;

        $mejorVendedor = User::find($mejorVendedorId);

        return response()->json([
            'mejor_vendedor' => $mejorVendedor,
            'ventas_fijo' => $ventasFijoMejor,
            'ventas_movil' => $ventasMovilMejor,
            'ventas_totales' => $maxVentas,
        ]);
    }
}
