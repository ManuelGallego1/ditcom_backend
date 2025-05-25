<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\GrupoUser;
use Illuminate\Http\Request;
use App\Models\Grupo;
use App\Http\Requests\GrupoRequest;
use App\Http\Requests\GrupoUpdateRequest;

class GrupoController extends Controller
{
    public function index(Request $request)
    {
        $query = Grupo::query();

        if ($request->filled('es_privado')) {
            $query->where('es_privado', $request->es_privado);
        }

        $grupos = $query->get();

        return response()->json([
            'data' => $grupos,
            'status' => 200
        ]);
    }

    public function show($id)
    {
        $grupo = Grupo::find($id);

        if (!$grupo) {
            return response()->json(['message' => 'Grupo no encontrado'], 404);
        }

        return response()->json([
            'data' => $grupo,
            'status' => 200
        ]);
    }

    public function store(GrupoRequest $request)
    {
        $grupo = Grupo::create([
            'nombre' => $request->nombre,
            'es_privado' => $request->es_privado,
        ]);

        return response()->json([
            'data' => $grupo,
            'status' => 201
        ]);
    }

    public function update(GrupoUpdateRequest $request, $id)
    {
        $grupo = Grupo::find($id);

        if (!$grupo) {
            return response()->json(['message' => 'Grupo no encontrado'], 404);
        }

        $grupo->update($request->validated());

        return response()->json([
            'data' => $grupo,
            'status' => 200
        ]);
    }

    public function destroy($id)
    {
        $grupo = Grupo::find($id);

        if (!$grupo) {
            return response()->json(['message' => 'Grupo no encontrado'], 404);
        }

        $grupo->delete();

        return response()->json(['message' => 'Grupo eliminado con éxito'], 200);
    }

    public function agregarUsuario(Request $request, $id)
    {
        $grupo = Grupo::find($id);

        if (!$grupo) {
            return response()->json(['message' => 'Grupo no encontrado'], 404);
        }

        $userId = $request->input('user_id');

        if (GrupoUser::where('grupo_id', $id)->where('user_id', $userId)->exists()) {
            return response()->json(['message' => 'El usuario ya está en el grupo'], 400);
        }

        GrupoUser::create([
            'grupo_id' => $id,
            'user_id' => $userId,
            'es_admin' => $request->input('es_admin', false),
        ]);

        return response()->json(['message' => 'Usuario agregado al grupo con éxito'], 200);
    }

    public function eliminarUsuario($id, $userId)
    {
        $grupoUser = GrupoUser::where('grupo_id', $id)->where('user_id', $userId)->first();

        if (!$grupoUser) {
            return response()->json(['message' => 'El usuario no está en el grupo'], 404);
        }

        $grupoUser->delete();

        return response()->json(['message' => 'Usuario eliminado del grupo con éxito'], 200);
    }

    public function cambiarRol(Request $request, $id, $userId)
    {
        $grupoUser = GrupoUser::where('grupo_id', $id)->where('user_id', $userId)->first();

        if (!$grupoUser) {
            return response()->json(['message' => 'El usuario no está en el grupo'], 404);
        }

        $grupoUser->update([
            'es_admin' => $request->es_admin
        ]);

        return response()->json(['message' => 'Rol del usuario actualizado con éxito'], 200);
    }

    public function misGrupos(Request $request)
    {
        $userId = $request->user()->id;

        $grupos = GrupoUser::where('user_id', $userId)->with('grupo')->get();

        return response()->json([
            'data' => $grupos,
            'status' => 200
        ]);
    }

    public function usuariosGrupo($id)
    {
        $grupo = Grupo::find($id);

        if (!$grupo) {
            return response()->json(['message' => 'Grupo no encontrado'], 404);
        }

        $usuarios = $grupo->usuarios()->with('user')->get();

        return response()->json([
            'data' => $usuarios,
            'status' => 200
        ]);
    }


}