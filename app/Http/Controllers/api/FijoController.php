<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Fijo;
use App\Http\Requests\FijoRequest;
use App\Http\Requests\FijoUpdateRequest;
use App\Exports\FijosExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Models\SedeVendedor;

class FijoController extends Controller
{

    public function index(Request $request)
    {
        $query = Fijo::with(['vendedor', 'sede', 'sede.coordinador', 'cliente'])
    
            ->when($request->filled('vendedor_id'), function ($q) use ($request) {
                $q->where('vendedor_id', $request->vendedor_id);
            })
    
            ->when($request->filled('sede_id'), function ($q) use ($request) {
                $q->where('sede_id', $request->sede_id);
            })
    
            ->when($request->boolean('ventas_pyme'), function ($q) {
                $q->where('tipo_producto', 'pyme');
            })
    
            ->when($request->filled('coordinador_id'), function ($q) use ($request) {
                $q->whereHas('sede', function ($q2) use ($request) {
                    $q2->where('coordinador_id', $request->coordinador_id);
                });
            });
    
        $order = $request->input('order', 'desc');
        $query->orderBy('fecha_instalacion', $order);
    
        $fijos = $query->paginate(30);
    
        return response()->json($fijos, 200);
    }
    
    public function show($id)
    {
        $fijo = Fijo::with(['vendedor', 'sede', 'sede.coordinador', 'cliente'])->find($id);
    
        if (!$fijo) {
            return response()->json(['message' => 'Fijo not found'], 404);
        }
    
        return response()->json([
            'data' => $fijo,
            'status' => 200
        ]);
    }

    public function store(FijoRequest $request)
    {
        $totalServicios = $request->total_servicios == '0' ? null : $request->total_servicios;
        $totalAdicionales = $request->total_adicionales == '0' ? null : $request->total_adicionales;
        $sedeVendedor = SedeVendedor::where('vendedor_id', $request->vendedor_id)->first();

        if (!$sedeVendedor) {
            return response()->json(['message' => 'El vendedor no tiene asociada ninguna sede', 'status' => 400], 400);
        }

        $fijo = Fijo::create([
            'fecha_instalacion' => $request->fecha_instalacion,
            'fecha_legalizacion' => $request->fecha_legalizacion,
            'servicios_adicionales' => $request->servicios_adicionales,
            'estrato' => $request->estrato,
            'cuenta' => $request->cuenta,
            'OT' => $request->OT,
            'tipo_producto' => $request->tipo_producto,
            'total_servicios' => $totalServicios,
            'total_adicionales' => $totalAdicionales,
            'cliente_cc' => $request->cliente_cc,
            'sede_id' => $sedeVendedor->sede_id,
            'vendedor_id' => $request->vendedor_id,
            'estado' => 'digitado',
            'convergente' => $request->convergente,
            'ciudad' => $request->ciudad,
        ]);

        return response()->json([
            'data' => $fijo,
            'status' => 201
        ]);
    }

    public function update(FijoUpdateRequest $request, $id)
    {
        $fijo = Fijo::find($id);

        if (!$fijo) {
            return response()->json(['message' => 'Fijo not found'], 404);
        }

        $fijo->update($request->validated());

        return response()->json([
            'data' => $fijo,
            'status' => 200
        ]);
    }

    public function destroy($id)
    {
        $fijo = Fijo::find($id);

        if (!$fijo) {
            return response()->json(['message' => 'Fijo not found'], 404);
        }

        $fijo->delete();

        return response()->json(['message' => 'Fijo deleted successfully'], 200);
    }

    public function export(Request $request)
    {

        $query = Fijo::query();
        $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date'))->endOfDay();

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
            $query->whereBetween('fecha_instalacion', [$startDate, $endDate]);
        }

        $fijos = $query->get();

        if ($fijos->isEmpty()) {
            return response()->json(['message' => 'No hay datos para exportar'], 404);
        }

        $fileName = "Fijo_{$startDate->format('Y-m-d')}_hasta_{$endDate->format('Y-m-d')}.xlsx";

        return Excel::download(new FijosExport($fijos), $fileName);
    }
}