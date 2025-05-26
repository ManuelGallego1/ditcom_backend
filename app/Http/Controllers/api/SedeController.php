<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sede;
use App\Http\Requests\SedeRequest;
use App\Http\Requests\SedeUpdateRequest;

class SedeController extends Controller
{
    public function index(Request $request)
    {
        $query = Sede::with('coordinador')->orderBy('nombre', 'desc');
    
        if (!$request->boolean('all')) {
            $query->where('activo', true);
        }
    
        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        if ($request->boolean('no_pagination')) {
            $sedes = $query->get();
            return response()->json([
                'data' => $sedes,
                'status' => 200
            ]);
        }
    
        $perPage = $request->input('per_page', 30);
        $sedes = $query->paginate($perPage);
    
        return response()->json($sedes, 200);
    }

    public function show($id)
    {
        $sede = Sede::find($id);
        if ($sede) {
            return response()->json([
                'data' => $sede,
                'status' => 200
            ]);
        } else {
            return response()->json(['message' => 'Sede not found'], 404);
        }
    }

    public function store(SedeRequest $request)
    {
        $sede = Sede::create([
            'nombre' => $request->nombre,
            'coordinador_id' => $request->coordinador_id,
            'activo' => true,
        ]);

        return response()->json([
            'data' => $sede,
            'status' => 201
        ]);
    }

    public function update(SedeUpdateRequest $request, $id)
    {
        $sede = Sede::find($id);
        if ($sede) {
            $sede->update($request->all());
            return response()->json([
                'data' => $sede,
                'status' => 200
            ]);
        } else {
            return response()->json(['message' => 'Sede not found'], 404);
        }
    }

    public function destroy($id)
    {
        $sede = Sede::find($id);
        if ($sede) {
            $sede->delete();
            return response()->json(['message' => 'Sede deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Sede not found'], 404);
        }
    }
}
