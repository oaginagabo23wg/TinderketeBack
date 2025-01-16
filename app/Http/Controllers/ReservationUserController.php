<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\TournamentUser;
use Illuminate\Support\Facades\Auth;

class ReservationUserController extends Controller
{
    public function addUser(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => $user], 401);  // Retorna un error 401 si no hay usuario autenticado
        }

        // Buscar la reserva
        $reservation = Reservation::find($request->id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        // Verificar si el usuario ya está en la reserva
        if ($reservation->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'User is already added to this reservation'], 400);
        }

        // Agregar el usuario a la reserva
        $reservation->users()->attach($user->id);

        return response()->json([
            'message' => 'User added to reservation successfully',
        ], 200);
    }
}