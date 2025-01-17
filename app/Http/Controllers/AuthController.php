<?php
// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use App\Models\User;

// class AuthController extends Controller
// {
//     public function login(Request $request)
//     {
//         $credentials = $request->only('email', 'password');

//         if (Auth::attempt($credentials)) {
//             $user = Auth::user();
//             return response()->json([
//                 'message' => 'Welcome!',
//                 'user' => $user
//             ]);
//         }

//         return response()->json(['message' => 'Incorrect credentials'], 401);
//     }
// }

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessTokenResult;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Verificar si las credenciales son correctas
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Crear un nuevo token para el usuario
            $token = $user->createToken('NombreDelToken')->plainTextToken;

            // Crear un registro en la tabla personal_access_tokens (relacionada con el usuario)
            $user->id_remember_token = $token;  // Aquí guardamos el token en el usuario
            $user->save();

            return response()->json([
                'message' => 'Welcome!',
                'user' => $user,
                'token' => $token,  // Este es el token generado
            ]);
        }

        return response()->json(['message' => 'Incorrect credentials'], 401);
    }
}
