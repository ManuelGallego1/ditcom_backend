<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;
use App\Http\Requests\PlanRequest;
use App\Http\Requests\PlanUpdateRequest;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        $query = Plan::query()->orderBy('codigo', 'desc');

        if (!$request->boolean('all')) {
            $query->where('activo', true);
        }

        if ($request->has('codigo')) {
            $query->where('codigo', $request->codigo);
        }

        if ($request->boolean('no_pagination')) {
            $planes = $query->get();
            return response()->json([
                'data' => $planes,
                'status' => 200
            ]);
        }

        $perPage = $request->input('per_page', 30);
        $planes = $query->paginate($perPage);

        return response()->json($planes, 200);
    }

    public function store(PlanRequest $request)
    {
        $plan = Plan::create([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'activo' => true,
        ]);

        if (!$plan) {
            return response()->json([
                'message' => 'Error al crear el registro del plan',
                'status' => 500
            ], 500);
        }

        return response()->json([
            'data' => $plan,
            'status' => 201
        ], 201);
    }

    public function show($id)
    {
        $plan = Plan::where('activo', true)->find($id);

        if (!$plan) {
            return response()->json([
                'message' => 'Error, plan no encontrado o inactivo',
                'status' => 404
            ], 404);
        }

        return response()->json([
            'data' => $plan,
            'status' => 200
        ]);
    }

    public function destroy($id)
    {
        $plan = Plan::find($id);

        if (!$plan) {
            return response()->json([
                'message' => 'Error, plan no encontrado',
                'status' => 404
            ], 404);
        }

        $plan->delete();

        return response()->json([
            'data' => 'Registro del plan eliminado',
            'status' => 200
        ]);
    }

    public function update(PlanUpdateRequest $request, $id)
    {
        $plan = Plan::find($id);

        if (!$plan) {
            return response()->json([
                'message' => 'Error, plan no encontrado',
                'status' => 404
            ], 404);
        }

        $plan->update($request->validated());

        return response()->json([
            'message' => 'Registro del plan actualizado parcialmente',
            'data' => $plan,
            'status' => 200
        ]);
    }

    public function getAllCodigos()
    {
        $codigos = Plan::where('activo', true)->select('codigo')->distinct()->pluck('codigo');
        return response()->json($codigos);
    }

    public function storeMultiple(PlanRequest $request)
    {
        try {
            foreach ($request->planes as $planData) {
                Plan::create(array_merge($planData, ['activo' => true]));
            }

            return response()->json([
                'message' => 'Todos los planes fueron registrados con éxito',
                'status' => 201
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrió un error al registrar los planes',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
}
