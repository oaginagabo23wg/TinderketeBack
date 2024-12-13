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
        $tournaments = Tournament::with('location')->get();

        return response()->json([
            'success' => true,
            'data' => $tournaments
        ], 200);
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
            'sport' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'price' => 'required|integer|min:0',
            'max_participants' => 'required|integer|min:0',
            'location_id' => 'required|exists:locations,id'
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
                'message' => 'Tournament not found'
            ], 404);
        }

        $validated = $request->validate([
            'sport' => 'required|string|max:255',
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
                'message' => 'Tournament not found'
            ], 404);
        }
    
        $tournament->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Tournament deleted successfully'
        ], 200);
    
    }
}