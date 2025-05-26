<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
    
        if (!$request->boolean('all')) {
            $query->where('activo', true);
        }
    
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }
    
        if ($request->boolean('no_pagination')) {
            $users = $query->get();
            return response()->json([
                'data' => $users,
                'status' => 200
            ]);
        }
    
        $perPage = $request->input('per_page', 30);
        $users = $query->paginate($perPage);
    
        return response()->json($users, 200);
    }

    public function show($id)
    {
        $user = User::find($id);
        if ($user) {
            return response()->json([
                'data' => $user,
                'status' => 200
            ]);
        } else {
            return response()->json(['message' => 'User not found'], 404);
        }
    }

    public function store(UserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'activo' => true,
        ]);

        return response()->json([
            'data' => $user,
            'status' => 201
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        if ($user) {
            return response()->json([
                'data' => $user,
                'status' => 200
            ]);
        } else {
            return response()->json(['message' => 'No se encontró el usuario autenticado.'], 404);
        }
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $data = $request->only(['name', 'username', 'password', 'role', 'activo']);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return response()->json([
            'data' => $user,
            'status' => 200
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }

}
