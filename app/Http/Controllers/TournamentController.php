<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tournaments = Tournament::all();

        return response()->json([
            'success' => true,
            'data' => $tournaments
        ], 200);
    }
    public function indexWithUsers($id = null)
{
    $query = Tournament::with('location', 'users');

    if ($id) {
        $tournament = $query->find($id);

        if (!$tournament) {
            return response()->json([
                'success' => false,
                'message' => 'Txapelketa ez da aurkitu'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatTournament($tournament)
        ], 200);
    }

    $tournaments = $query->get()->map(fn($tournament) => $this->formatTournament($tournament));

    return response()->json([
        'success' => true,
        'data' => $tournaments
    ], 200);
}

/**
 * Formatea un torneo para incluir las imágenes con URLs completas y el conteo de participantes.
 */
private function formatTournament($tournament)
{
    $tournament->location->img = url($tournament->location->img);
    $tournament->users->each(fn($user) => $user->img = url($user->img));

    return [
        'id' => $tournament->id,
        'title' => $tournament->title,
        'location' => $tournament->location,
        'date' => $tournament->date,
        'time' => $tournament->time,
        'description' => $tournament->description,
        'participants_count' => $tournament->users->count(), // Cuenta los usuarios inscritos
        'max_participants' => $tournament->max_participants,
        'price' => $tournament->price,
        'image' => $tournament->location->img ?? "comingsoon.png",
        'users' => $tournament->users,
    ];
}

    /**
     * Show the form for creating a new resource.
    */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required',
            'price' => 'required|integer|min:0',
            'max_participants' => 'required|integer|min:0',
            'location_id' => 'required|exists:locations,id',
        ]);

        $tournament = Tournament::create($validated);

        return response()->json([
            'success' => true,
            'data' => $tournament
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tournament = Tournament::find($id);

        if (!$tournament) {
            return response()->json([
                'success' => false,
                'message' => 'Txapelketa ez da aurkitu'
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'price' => 'required|integer|min:0',
            'max_participants' => 'required|integer|min:0',
            'location_id' => 'required|exists:locations,id'
        ]);

        $tournament->update($validated);

        return response()->json([
            'success' => true,
            'data' => $tournament
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tournament = Tournament::find($id);

        if (!$tournament) {
            return response()->json([
                'success' => false,
                'message' => 'Txapelketa ez da aurkitu'
            ], 404);
        }
    
        $tournament->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Ongi ezabatu da txapelketa'
        ], 200);
    
    }

    //Funcion que nos devuelve la cantidad de participantes por tipo de ubicación (trinkete o frontón).
    public function getPopularTournaments()
{
    // Obtener todos los torneos con su respectiva ubicación y usuarios registrados
    $tournaments = Tournament::with('location', 'users')->get();

    // Contadores para los dos tipos de torneos
    $data = [
        'fronton' => 0,
        'trinkete' => 0
    ];

    // Recorremos los torneos para sumar los participantes según el tipo de ubicación
    foreach ($tournaments as $tournament) {
        $participantsCount = $tournament->users->count(); // Contamos los usuarios registrados

        // Verificamos si el torneo es de frontón o trinkete y sumamos los participantes
        if ($tournament->location->type === 'frontoiak') {
            $data['fronton'] += $participantsCount;
        } elseif ($tournament->location->type === 'trinketeak') {
            $data['trinkete'] += $participantsCount;
        }
    }

    return response()->json([
        'success' => true,
        'data' => $data
    ], 200);
}

}