<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TournamentUser;
use App\Models\Tournament;

class TournamentUserController extends Controller
{
    public function bookIn($id, Request $request)
    {
        $userId = Auth::id(); // Obtiene el ID del usuario autenticado

        if (!$userId) {
            return response()->json(['message' => 'Usuario no autenticado'], 401);
        }

        // Verifica si el torneo existe
        $tournament = Tournament::find($id);
        if (!$tournament) {
            return response()->json(['message' => 'Torneo no encontrado'], 404);
        }

        // Verifica si el usuario ya está registrado
        if (TournamentUser::where('tournament_id', $id)->where('user_id', $userId)->exists()) {
            return response()->json(['message' => 'Ya estás registrado en este torneo'], 400);
        }

        // Verifica si hay espacio disponible en el torneo
        $currentParticipants = $tournament->users()->count(); // Usando relación
        if ($currentParticipants >= $tournament->max_participants) {
            return response()->json(['message' => 'El torneo ya está lleno'], 400);
        }

        try {
            // Crea el registro en la tabla tournament_users
            $registration = TournamentUser::create([
                'tournament_id' => $id,
                'user_id' => $userId,
            ]);

            return response()->json([
                'message' => 'Registro exitoso',
                'data' => $registration,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al registrar al usuario',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
