<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id = null)
    {
        if ($id) {
            $mapa = Location::find($id);
    
            // Verificar si el torneo existe
            if (!$mapa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mapa ez da aurkitu'
                ], 404);
            }
    
            return response()->json([
                'success' => true,
                'data' => $mapa
            ], 200);
        }

        $mapa = Location::all(); //Erabiltzaile guztiak hartu

        return response()->json([
            'success' => true,
            'data' => $mapa
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
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'img' => 'required|string|max:255',
            'iframe' => 'required|string|max:1024',
            'url' => 'required|string|max:512'
        ]);

        $location = Location::create($validated);

        return response()->json([
            'success' => true,
            'data' => $location,
        ]);

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
        $location = Location::find($id);
    
        if (!$location) {
            return response()->json(['message' => 'Location not found'], 404);
        }
    
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'img' => 'required|string|max:255',
            'iframe' => 'required|string|max:1024',
            'url' => 'required|string|max:512',
        ]);
    
        $location->update($validated);
    
        return response()->json([
            'success' => true,
            'data' => $location,
        ]);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $location = Location::find($id);

        if (!$location) {
            return response()->json(['message' => 'Location not found'], 404);
        }

        $location->delete();

        return response()->json([
            'success' => true,
            'message' => 'Location deleted successfully',
        ]);

    }
    public function delete(Request $request, $id)
    {
        // Buscar el usuario por ID
        $location = Location::find($id);
    
        // Verificar si el usuario existe
        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado',
            ], 404);
        }
    
        // Eliminar el usuario
        $location->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado correctamente'
        ], 200);
    }
    
}
