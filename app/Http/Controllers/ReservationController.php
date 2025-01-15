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
        $reservations = Reservation::with('users') // Asume que tienes una relación con usuarios
            ->get()
            ->map(function ($reservation) {
                return [
                    'id' => $reservation->id,
                    'date' => $reservation->date,
                    'time' => $reservation->time,
                    'type' => $reservation->location->type,
                    'location' => $reservation->location->name ?? 'Unknown',
                    'players' => $reservation->users->map(function ($user) {
                        return [
                            'name' => $user->name,
                            'image' => $user->img,
                        ];
                    }),
                    'price' => number_format($reservation->price, 2) . '€',
                ];
            });

        return response()->json($reservations);
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

        // Comprobar si ya existe una reserva con la misma fecha, hora y ubicación
        $existingReservation = Reservation::where('date', $validated['date'])
            ->where('time', $validated['time'])
            ->where('location_id', $validated['location_id'])
            ->first();

        if ($existingReservation) {
            return response()->json([
                'message' => 'A reservation already exists for this location and time.',
            ], 422); // 422 Unprocessable Entity
        }

        // Agregar valores por defecto
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


    public function reservationUser()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $reservations = $user->reservations()->with('location')->get();

        return response()->json([
            'success' => true,
            'data' => $reservations
        ]);
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
            'user_id' => 'required|exists:users,id', // Validamos que el user_id exista
        ]);

        // Buscar la reserva
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404); // Si no existe la reserva
        }

        // Verificar si el usuario ya está agregado a la reserva
        if ($reservation->users()->where('user_id', $validated['user_id'])->exists()) {
            return response()->json(['message' => 'User is already added to this reservation'], 400);
        }

        // Agregar el usuario a la reserva
        $reservation->users()->attach($validated['user_id']); // Esta es la línea que agrega al usuario a la tabla intermedia

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