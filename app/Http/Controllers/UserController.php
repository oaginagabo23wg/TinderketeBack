<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class UserController extends Controller
{
    
    public function register(Request $request)
    {
        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'birth_date' => 'required|date|before:-18 years', // Asegura que el usuario tenga más de 18 años
            'admin' => 'required|boolean',
        ]);

        // Si la validación falla, devolver errores
        if ($validator->fails()) {
            return response()->json([
                'Errorea' => $validator->errors()
            ], 422);
        }

        // Crear un nuevo usuario
        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'birth_date' => Carbon::parse($request->birth_date), // Guardamos la fecha de nacimiento
            'admin' => $request->admin,
        ]);

        // Devolver la respuesta con el nuevo usuario creado
        return response()->json([
            'message' => 'Erabiltzailea ongi sortu da.',
            'user' => $user
        ], 201);
    }
}
