<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mensaje;
use App\Http\Requests\MensajeRequest;
use Illuminate\Support\Facades\Storage;

class MensajeController extends Controller
{
    public function index(Request $request, $grupoId)
    {
        $mensajes = Mensaje::where('grupo_id', $grupoId)
            ->orderBy('created_at', 'asc')
            ->get();

        if ($request->has('leido')) {
            $leido = $request->input('leido');
            $mensajes = $mensajes->where('leido', $leido);
        }

        return response()->json([
            'data' => $mensajes,
            'status' => 200
        ]);
    }

    public function store(MensajeRequest $request)
    {
        $filePath = null;

        if ($request->hasFile('file')) {
            $originalFileName = $request->file('file')->getClientOriginalName();

            $timestamp = time();
            $fileNameWithTimestamp = pathinfo($originalFileName, PATHINFO_FILENAME) . "_{$timestamp}." . $request->file('file')->getClientOriginalExtension();

            $destinationPath = base_path('public_html/data');

            $request->file('file')->move($destinationPath, $fileNameWithTimestamp);

            $filePath = 'data/' . $fileNameWithTimestamp;
        }

        $mensaje = Mensaje::create([
            'grupo_id' => $request->grupo_id,
            'user_id' => $request->user_id,
            'contenido' => $request->contenido,
            'file_path' => $filePath,
            'leido' => false,
        ]);

        return response()->json([
            'data' => $mensaje,
            'status' => 201
        ]);
    }

    public function markAllAsRead(Request $request, $grupoId)
    {
        $userId = $request->user_id;

        $mensajes = Mensaje::where('grupo_id', $grupoId)
            ->where('user_id', '!=', $userId)
            ->where('leido', false)
            ->update(['leido' => true]);

        return response()->json([
            'message' => 'Todos los mensajes del grupo marcados como leídos',
            'updated_count' => $mensajes,
            'status' => 200
        ]);
    }

    public function destroy($id)
    {
        $mensaje = Mensaje::find($id);

        if (!$mensaje) {
            return response()->json(['message' => 'Mensaje no encontrado'], 404);
        }

        if ($mensaje->file_path) {
            Storage::disk('public')->delete($mensaje->file_path);
        }

        $mensaje->delete();

        return response()->json(['message' => 'Mensaje eliminado con éxito'], 200);
    }
}