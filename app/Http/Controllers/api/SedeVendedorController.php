<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SedeVendedor;

class SedeVendedorController extends Controller
{
    public function index()
    {
    $sedeVendedores = SedeVendedor::with(['sede', 'vendedor'])->paginate(10); // Puedes ajustar el número de ítems por página

    return response()->json($sedeVendedores);
    }

    public function show($id)
    {
        $sedeVendedor = SedeVendedor::find($id);
        if ($sedeVendedor) {
            return response()->json([
                'data' => $sedeVendedor,
                'status' => 200
            ]);
        } else {
            return response()->json(['message' => 'SedeVendedor not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'vendedor_id' => 'required|exists:users,id',
            'sede_id' => 'required|exists:sedes,id',
        ]);

        $sedeVendedor = SedeVendedor::create($validatedData);

        return response()->json([
            'data' => $sedeVendedor,
            'status' => 201
        ]);
    }

    public function update(Request $request, $id)
    {
        $sedeVendedor = SedeVendedor::find($id);
        if ($sedeVendedor) {
            $validatedData = $request->validate([
                'vendedor_id' => 'sometimes|exists:users,id',
                'sede_id' => 'sometimes|exists:sedes,id',
            ]);

            $sedeVendedor->update($validatedData);

            return response()->json([
                'data' => $sedeVendedor,
                'status' => 200
            ]);
        } else {
            return response()->json(['message' => 'SedeVendedor not found'], 404);
        }
    }

    public function destroy($id)
    {
        $sedeVendedor = SedeVendedor::find($id);
        if ($sedeVendedor) {
            $sedeVendedor->delete();
            return response()->json(['message' => 'SedeVendedor deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'SedeVendedor not found'], 404);
        }
    }
}
