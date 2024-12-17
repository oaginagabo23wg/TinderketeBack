<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class UserController extends Controller
{
    // // Middleware para proteger estas rutas con Sanctum
    // public function __construct()
    // {
    //     $this->middleware('auth:sanctum');
    //     //console.log('kaixo');
    // }

    public function register(Request $request)
    {
        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'birth_date' => 'required|date|before:-18 years',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'birth_date' => Carbon::parse($request->birth_date),
            'admin' => '0',
            'hometown' => $request->hometown ?? null,
            'telephone' => $request->telephone ?? null,
        ]);

        return response()->json([
            'message' => 'Usuario creado con éxito',
            'user' => $user
        ], 201);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado.'
            ], 404);
        }

        return response()->json([
            'user' => $user->makeHidden('password') // Ocultamos la contraseña
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado.'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'surname' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8|confirmed',
            'birth_date' => 'sometimes|date|before:-18 years',
            'admin' => 'sometimes|boolean',
            'hometown' => 'sometimes|string',
            'telephone' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update([
            'name' => $request->name ?? $user->name,
            'surname' => $request->surname ?? $user->surname,
            'email' => $request->email ?? $user->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'birth_date' => $request->birth_date ? Carbon::parse($request->birth_date) : $user->birth_date,
            'admin' => $request->admin ?? $user->admin,
            'hometown' => $request->hometown ?? $user->hometown,
            'telephone' => $request->telephone ?? $user->telephone,
        ]);

        return response()->json([
            'message' => 'Usuario actualizado con éxito',
            'user' => $user
        ], 200);
    }
}
