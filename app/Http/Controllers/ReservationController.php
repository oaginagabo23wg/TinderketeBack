<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\User; // Importar el modelo User
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Display a listing of reservations.
     */
    public function index()
    {
        // Obtener todos los partidos con la ubicación asociada
        $reservations = Reservation::all();
        return response()->json($reservations, 200);
    }

    /**
     * Store a newly created reservation in storage.
     */
    public function store(Request $request)
    {
        // Validar datos de entrada
        $validated = $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'location_id' => 'required|exists:locations,id',
        ]);

        $validated['price'] = 20;
        $validated['public'] = 0;

        // Crear la nueva reserva
        $reservation = Reservation::create($validated);

        // Obtener el id del usuario autenticado
        $userId = Auth::id();

        // Asegurarse de que la relación se cree correctamente en la tabla pivote
        $reservation->users()->attach($userId);

        return response()->json([
            'message' => 'Reservation created successfully',
            'data' => $reservation
        ], 201);
    }


    /**
     * Display the specified reservation.
     */
    public function show($id)
    {
        // Buscar el partido con su ubicación
        $reservation = Reservation::with('location')->find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        return response()->json($reservation, 200);
    }

    /**
     * Update the specified reservation in storage.
     */
    public function update(Request $request, $id)
    {
        // Buscar el partido
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        // Validar datos de entrada
        $validated = $request->validate([
            'date' => 'sometimes|date',
            'time' => 'sometimes|date_format:H:i',
            'price' => 'sometimes|numeric|min:0',
            'location_id' => 'sometimes|exists:locations,id',
            'public' => 'sometimes|boolean',
        ]);

        // Actualizar los datos del partido
        $reservation->update($validated);

        return response()->json([
            'message' => 'Reservation updated successfully',
            'data' => $reservation
        ], 200);
    }

    /**
     * Remove the specified reservation from storage.
     */
    public function destroy($id)
    {
        // Buscar el partido
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        // Eliminar el partido
        $reservation->delete();

        return response()->json(['message' => 'Reservation deleted successfully'], 200);
    }

    /**
     * Add a user to a reservation.
     */
    public function addUser(Request $request, $id)
    {
        // Validar el usuario
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Buscar el partido
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        // Agregar el usuario al partido
        $reservation->users()->attach($validated['user_id']);

        return response()->json([
            'message' => 'User added to reservation successfully',
        ], 200);
    }

    /**
     * Remove a user from a reservation.
     */
    public function removeUser(Request $request, $id)
    {
        // Validar el usuario
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Buscar el partido
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        // Remover el usuario del partido
        $reservation->users()->detach($validated['user_id']);

        return response()->json([
            'message' => 'User removed from reservation successfully',
        ], 200);
    }
}
