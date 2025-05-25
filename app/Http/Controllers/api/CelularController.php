<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Celular;
use App\Http\Requests\CelularRequest;
use App\Http\Requests\CelularUpdateRequest;

class CelularController extends Controller
{
   public function index(Request $request)
{
    $query = Celular::query()->orderBy('marca', 'desc');

    if (!$request->boolean('all')) {
        $query->where('activo', true);
    }

    if ($request->filled('marca')) {
        $query->where('marca', 'like', '%' . $request->marca . '%');
    }

    $celulares = $query->paginate(30);
    
    return response()->json($celulares, 200);
}


    public function store(CelularRequest $request)
    {
        $celular = Celular::create([
            'modelo' => $request->modelo,
            'marca' => $request->marca,
            'activo' => true,
        ]);

        if (!$celular) {
            return response()->json([
                'message' => 'Error al crear el registro del celular',
                'status' => 500
            ], 500);
        }

        return response()->json([
            'data' => $celular,
            'status' => 201
        ], 201);
    }

    public function show($id)
    {
        $celular = Celular::where('activo', true)->find($id);

        if (!$celular) {
            return response()->json([
                'message' => 'Error, celular no encontrado o inactivo',
                'status' => 404
            ], 404);
        }

        return response()->json([
            'data' => $celular,
            'status' => 200
        ]);
    }

    public function destroy($id)
    {
        $celular = Celular::find($id);

        if (!$celular) {
            return response()->json([
                'message' => 'Error, celular no encontrado',
                'status' => 404
            ], 404);
        }

        $celular->delete();

        return response()->json([
            'data' => 'Registro del celular eliminado',
            'status' => 200
        ]);
    }

    public function update(CelularUpdateRequest $request, $id)
    {
        $celular = Celular::find($id);

        if (!$celular) {
            return response()->json([
                'message' => 'Error, celular no encontrado',
                'status' => 404
            ], 404);
        }

        $data = $request->validated();

        if ($request->has('activo')) {
            $data['activo'] = $request->activo;
        }

        $celular->update($data);

        return response()->json([
            'message' => 'Registro del celular actualizado parcialmente',
            'data' => $celular,
            'status' => 200
        ]);
    }
    
    public function getAllMarcas()
    {
        $marcas = Celular::where('activo', true)
            ->select('marca')
            ->distinct()
            ->pluck('marca');
    
        return response()->json([
            'data' => $marcas,
            'status' => 200
        ]);
    }


    public function getModelosByMarca(string $marca)
    {
        $modelos = Celular::where('activo', true)
            ->where('marca', $marca)
            ->select('id', 'modelo')
            ->distinct()
            ->get();

        return response()->json([
            'data' => $modelos,
            'status' => 200
        ]);
    }

    
    public function storeMultiple(CelularRequest $request)
    {
        try {
            foreach ($request->celulares as $celularData) {
                Celular::create(array_merge($celularData, ['activo' => true]));
            }

            return response()->json([
                'message' => 'Todos los celulares fueron registrados con éxito',
                'status' => 201
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrió un error al registrar los celulares',
                'error' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
}