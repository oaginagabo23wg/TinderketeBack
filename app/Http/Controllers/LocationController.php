<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locations = Location::all(); // Obtiene todas las ubicaciones
        return response()->json([
            'success' => true,
            'data' => $locations,
        ]);

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
            'url' => 'required|string|max:512'
        ]);

        $location->update([$validated]);

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
//     public function delete(Request $request, $id)
// {
//     // Buscar al usuario por ID
//     $location = Location::find($id);

//     // Verificar si el usuario existe
//     if (!$location) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Usuario no encontrado',
//         ], 404);
//     }

//     // Actualizar el campo aktibatua a 0 para desactivar al usuario
//     $location->aktibatua = 0;
//     $location->save();

//     return response()->json([
//         'success' => true,
//         'message' => 'Usuario desactivado correctamente',
//         'location' => $location
//     ], 200);
// }
}
