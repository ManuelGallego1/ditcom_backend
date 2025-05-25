<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movil;
use App\Http\Requests\MovilRequest;
use Carbon\Carbon;
use App\Exports\MovilesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\MovilUpdateRequest;
use App\Models\SedeVendedor;

class MovilController extends Controller
{
    public function index(Request $request)
    {
        $query = Movil::with(['vendedor', 'sede', 'sede.coordinador', 'cliente', 'plan']);
    
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
    
        $order = $request->input('order', 'desc');
        $query->orderBy('created_at', $order);
    
        $moviles = $query->paginate(50);
    
        return response()->json($moviles, 200);
    }

    public function show($id)
    {
        $movil = Movil::find($id);

        if (!$movil) {
            return response()->json(['message' => 'Movil not found'], 404);
        }

        return response()->json([
            'data' => $movil,
            'status' => 200
        ]);
    }

    public function store(MovilRequest $request)
    {
        $sedeVendedor = SedeVendedor::where('vendedor_id', $request->vendedor_id)->first();

        if (!$sedeVendedor) {
            return response()->json([
                'message' => 'Error, no se encontró una sede asignada para el vendedor',
                'status' => 400
            ], 400);
        }

        $sede = $sedeVendedor->sede;

        if (!$sede || !$sede->coordinador_id) {
            return response()->json([
                'message' => 'Error, no se encontró un coordinador asignado para la sede',
                'status' => 400
            ], 400);
        }

        $movil = Movil::create([
            'min' => $request->min,
            'imei' => $request->imei,
            'iccid' => $request->iccid,
            'tipo' => $request->tipo,
            'plan_id' => $request->plan_id,
            'celulares_id' => $request->celulares_id,
            'cliente_cc' => $request->cliente_cc,
            'factura' => $request->factura,
            'ingreso_caja' => $request->ingreso_caja,
            'tipo_producto' => $request->tipo_producto,
            'valor_recarga' => $request->valor_recarga,
            'valor_total' => $request->valor_total,
            'vendedor_id' => $request->vendedor_id,
            'sede_id' => $sedeVendedor->sede_id,
            'financiera' => $request->financiera,
            'coordinador_id' => $sede->coordinador_id,
            'estado' => 'pendiente',
        ]);

        return response()->json([
            'data' => $movil,
            'status' => 201
        ]);
    }

    public function update(MovilUpdateRequest $request, $id)
    {
        $movil = Movil::find($id);

        if (!$movil) {
            return response()->json(['message' => 'Movil not found'], 404);
        }

        $movil->update($request->validated());

        return response()->json([
            'data' => $movil,
            'status' => 200
        ]);
    }

    public function destroy($id)
    {
        $movil = Movil::find($id);

        if (!$movil) {
            return response()->json(['message' => 'Movil not found'], 404);
        }

        $movil->delete();

        return response()->json(['message' => 'Movil deleted successfully'], 200);
    }

    public function export(Request $request)
    {
        $query = Movil::query();
        $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();

        if ($request->filled('vendedor_id')) {
            $query->where('vendedor_id', $request->vendedor_id);
        }

        if ($request->filled('sede_id')) {
            $query->where('sede_id', $request->sede_id);
        }

        if ($request->filled('tipo_producto')) {
            $query->where('tipo_producto', $request->tipo_producto);
        }

        if ($request->filled('coordinador_id')) {
            $query->whereHas('sede', function ($q) use ($request) {
                $q->where('coordinador_id', $request->coordinador_id);
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        }

        $moviles = $query->get();

        if ($moviles->isEmpty()) {
            return response()->json(['message' => 'No hay datos para exportar'], 404);
        }

        $fileName = "Movil_{$startDate->format('Y-m-d')}_hasta_{$endDate->format('Y-m-d')}.xlsx";

        return Excel::download(new MovilesExport($moviles), $fileName);
    }

    public function getStatistics(Request $request)
    {
        $query = Movil::query();

        if ($request->filled('vendedor_id')) {
            $query->where('vendedor_id', $request->vendedor_id);
        }

        if ($request->filled('sede_id')) {
            $query->where('sede_id', $request->sede_id);
        }

        if ($request->filled('coordinador_id')) {
            $query->whereHas('sede', function ($q) use ($request) {
                $q->where('coordinador_id', $request->coordinador_id);
            });
        }

        if ($request->boolean('ventas_pyme')) {
            $query->where('tipo_producto', 'pyme');
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            $query->whereBetween('updated_at', [$startDate, $endDate]);
        }

        $totalMoviles = $query->count();
        $totalPendientes = $query->where('estado', 'pendiente')->count();
        $totalActivos = $query->where('estado', 'activo')->count();
        $totalInactivos = $query->where('estado', 'inactivo')->count();

        return response()->json([
            'total_moviles' => $totalMoviles,
            'total_pendientes' => $totalPendientes,
            'total_activos' => $totalActivos,
            'total_inactivos' => $totalInactivos,
        ]);
    }
}