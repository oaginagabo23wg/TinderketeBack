<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CreateUserController extends Controller
{
    
    public function register(Request $request)
    {
        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'izena' => 'required|string|max:255',
            'abizenak' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'pasahitza' => 'required|string|min:8|confirmed',
            'jaiotzeData' => 'required|date|before:-18 years', // Asegura que el usuario tenga más de 18 años
        ]);

        // Si la validación falla, devolver errores
        if ($validator->fails()) {
            return response()->json([
                'Errorea' => $validator->errors()
            ], 422);
        }

        // Crear un nuevo usuario
        $user = User::create([
            'izena' => $request->izena,
            'abizenak' => $request->abizenak,
            'email' => $request->email,
            'pasahitza' => Hash::make($request->pasahitza),
            'jaiotzeData' => Carbon::parse($request->jaiotzeData), // Guardamos la fecha de nacimiento
        ]);

        // Devolver la respuesta con el nuevo usuario creado
        return response()->json([
            'message' => 'Erabiltzailea ongi sortu da.',
            'user' => $user
        ], 201);
    }
}
