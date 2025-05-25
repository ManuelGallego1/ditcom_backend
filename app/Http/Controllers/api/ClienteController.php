<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Http\Requests\ClienteRequest;
use App\Http\Requests\ClienteUpdateRequest;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $clientes = Cliente::all();

        return response()->json([
            'data' => $clientes,
            'status' => 200
        ]);
    }

    public function show($id)
    {
        $cliente = Cliente::find($id);
        if ($cliente) {
            return response()->json([
                'data' => $cliente,
                'status' => 200
            ]);
        } else {
            return response()->json(['message' => 'Cliente not found'], 404);
        }
    }

    public function store(ClienteRequest $request)
    {
        $cliente = Cliente::create([
            'cc' => $request->cc,
            'p_nombre' => $request->p_nombre,
            's_nombre' => $request->s_nombre,
            'p_apellido' => $request->p_apellido,
            's_apellido' => $request->s_apellido,
            'email' => $request->email,
            'numero' => $request->numero,
        ]);

        return response()->json([
            'data' => $cliente,
            'status' => 201
        ]);
    }

    public function update(ClienteUpdateRequest $request, $id)
    {
        $cliente = Cliente::find($id);
        if ($cliente) {
            $cliente->update($request->all());
            return response()->json([
                'data' => $cliente,
                'status' => 200
            ]);
        } else {
            return response()->json(['message' => 'Cliente not found'], 404);
        }
    }

    public function destroy($id)
    {
        $cliente = Cliente::find($id);
        if ($cliente) {
            $cliente->delete();
            return response()->json(['message' => 'Cliente deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Cliente not found'], 404);
        }
    }
}
