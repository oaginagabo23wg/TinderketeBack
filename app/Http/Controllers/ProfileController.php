<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        // Validar los datos recibidos
        $request->validate([
            'name' => 'required|string|max:255',
            'img' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validación de la imagen
        ]);

        // Obtener el usuario autenticado
        $user = Auth::user();

        // Actualizar el nombre
        $user->name = $request->input('name');

        // Si se sube una nueva imagen
        if ($request->hasFile('img')) {
            // Eliminar la imagen anterior si existe
            if ($user->img && Storage::exists($user->img)) {
                Storage::delete($user->img);
            }

            // Guardar la nueva imagen
            $path = $request->file('img')->store('profiles', 'public');
            $user->img = $path; // Guardar la ruta de la imagen en la base de datos
        }

        // Guardar los cambios
        $user->save();

        // Devolver una respuesta exitosa
        return response()->json([
            'message' => 'Profila ongi aldatu da.',
            'data' => $user
        ]);
    }
}
