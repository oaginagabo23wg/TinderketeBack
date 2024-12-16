<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class UserController extends Controller
{
    // Middleware para proteger estas rutas con Sanctum
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function register(Request $request)
    {
        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'birth_date' => 'required|date|before:-18 years',
            'admin' => 'required|boolean',
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
            'admin' => $request->admin,
            'jaioterria' => $request->jaioterria ?? null,
            'telefonoa' => $request->telefonoa ?? null,
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
            'jaioterria' => 'sometimes|string',
            'telefonoa' => 'sometimes|string',
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
            'jaioterria' => $request->jaioterria ?? $user->jaioterria,
            'telefonoa' => $request->telefonoa ?? $user->telefonoa,
        ]);

        return response()->json([
            'message' => 'Usuario actualizado con éxito',
            'user' => $user
        ], 200);
    }
}
