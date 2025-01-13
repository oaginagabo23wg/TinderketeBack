<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Mail\UserCreatedMail;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class UserController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index($id = null)
    {
        if ($id) {
            $erabil = User::find($id);
    
            // Verificar si el torneo existe
            if (!$erabil) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erabiltzailea ez da aurkitu'
                ], 404);
            }
    
            return response()->json([
                'success' => true,
                'data' => $erabil
            ], 200);
        }

        $user = User::all(); //Erabiltzaile guztiak hartu

        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
        
    }

    

    public function register(Request $request)
    {
        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'birth_date' => 'required|date|before:-18 years',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validar imagen opcional
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Manejo de la imagen (predeterminada o cargada)
        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('users', 'public') // Almacenar imagen en storage/app/public/users
            : 'public/perfiltxuri.png'; // Ruta predeterminada para la imagen (debería estar dentro de public o storage)

        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'birth_date' => Carbon::parse($request->birth_date),
            'admin' => '0', // Valor predeterminado de 'admin'
            'hometown' => $request->hometown ?? null,
            'telephone' => $request->telephone ?? null,
            'image' => $imagePath,
            'aktibatua' => '1', 
        ]);

        // Enviar el correo al usuario recién creado
        Mail::to($user->email)->send(new UserCreatedMail($user));

        // Crear un token para el usuario
        $token = $user->createToken('auth_token')->plainTextToken;

        // Generar la URL pública de la imagen
        $imageUrl = asset('storage/' . $imagePath); // Asegúrate de que storage:link esté creado

        // Devolver los datos del usuario y el token
        return response()->json([
            'message' => 'Usuario creado con éxito',
            'user' => $user,
            'token' => $token,
            'image_url' => $imageUrl, // Incluir la URL pública de la imagen
        ], 201);
    }


    public function update(Request $request, string $id)
    {
        $erabil = User::find($id);

        if (!$erabil) {
            return response()->json([
                'success' => false,
                'message' => 'erabil not found'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'surname' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8|confirmed',
            'birth_date' => 'sometimes|date|before:-18 years',
            'admin' => 'sometimes|boolean',
            'hometown' => 'sometimes|string',
            'telephone' => 'sometimes|string',
            //'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validar imagen opcional
            'aktibatua' => 'sometimes|boolean',
        ]);

        // Solo actualizar la imagen si se proporciona una nueva
    if ($request->hasFile('image')) {
        // Si se sube una nueva imagen, validarla y guardarla
        $imagePath = $request->file('image')->store('images', 'public');
        $validated['image'] = $imagePath;  // Guardamos la ruta de la imagen
    }

        $erabil->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $erabil
        ], 200);
    }


    public function sendEmail(Request $request)
{
    // Validar los datos del formulario
    $validated = $request->validate([
        'id' => 'required|integer',
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'message' => 'required|string',
    ]);

    // Crear una instancia del mailable con los datos validados
    $contactMail = new ContactMail($validated);

    // Enviar el correo a la dirección deseada
    Mail::to('tinderkete@gmail.com')->send($contactMail);

    // Retornar respuesta JSON de éxito
    return response()->json(['message' => 'Correo enviado correctamente'], 200);
}



    // public function update(Request $request, $id)
    // {
    //     $user = User::find($id);

    //     if (!$user) {
    //         return response()->json([
    //             'message' => 'Usuario no encontrado.'
    //         ], 404);
    //     }

    //     // Validación de datos del formulario
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'sometimes|string|max:255',
    //         'surname' => 'sometimes|string|max:255',
    //         'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
    //         'password' => 'sometimes|string|min:8|confirmed',
    //         'birth_date' => 'sometimes|date|before:-18 years',
    //         'admin' => 'sometimes|boolean',
    //         'hometown' => 'sometimes|string',
    //         'telephone' => 'sometimes|string',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validar imagen opcional
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }

    //     // // Manejo de la imagen
    //     // if ($request->hasFile('image')) {
    //     //     // Eliminar la imagen anterior si existe
    //     //     if ($user->image && file_exists(storage_path('app/public/' . $user->image))) {
    //     //         unlink(storage_path('app/public/' . $user->image));
    //     //     }

    //     //     // Almacenar la nueva imagen
    //     //     $imagePath = $request->file('image')->store('users', 'public');
    //     //     $user->image = $imagePath;
    //     // }

    //     // Actualizar los demás campos
    //     $user->update([
    //         'name' => $request->name ?? $user->name,
    //         'surname' => $request->surname ?? $user->surname,
    //         'email' => $request->email ?? $user->email,
    //         'password' => $request->password ? Hash::make($request->password) : $user->password,
    //         'birth_date' => $request->birth_date ? Carbon::parse($request->birth_date) : $user->birth_date,
    //         'admin' => $request->admin ?? $user->admin,
    //         'hometown' => $request->hometown ?? $user->hometown,
    //         'telephone' => $request->telephone ?? $user->telephone,
    //     ]);

    //     // Guardar los cambios
    //     //$user->save();

    //     // // Generar la URL pública de la imagen
    //     // $imageUrl = asset('storage/' . $user->image); // Asegúrate de que storage:link esté creado

    //     return response()->json([
    //         'message' => 'Usuario actualizado con éxito',
    //         'user' => $user,
    //         // 'image_url' => $imageUrl, // Incluir la URL pública de la imagen
    //     ], 200);
    // }

    public function login(Request $request)
{
    // Validar los datos del login
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    // Verificar si el usuario existe y si la contraseña es correcta
    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        // Si no se encuentra el usuario o la contraseña es incorrecta
        return response()->json([
            'message' => 'Las credenciales proporcionadas no son válidas.',
        ], 401);
    }

    // Crear un token para el usuario
    $token = $user->createToken('auth_token')->plainTextToken;

    // Devolver el token y los datos del usuario
    return response()->json([
        'user' => $user,  // Incluye toda la información del usuario, incluyendo el valor de 'admin'
        'token' => $token,
    ]);
}


    public function getUser(Request $request)
{
    // Recupera el usuario autenticado
    $user = $request->user(); // Asumiendo que estás utilizando Sanctum para autenticación

    if (!$user) {
        return response()->json([
            'message' => 'Usuario no autenticado',
        ], 401);
    }

    // Genera la URL pública de la imagen
    $imageUrl = asset('storage/' . $user->image); // Asegúrate de que storage:link esté creado

    return response()->json([
        'user' => $user,
        'image_url' => $imageUrl, // Incluir la URL pública de la imagen
    ]);
}

// Añadir este método a tu controlador UserController

public function delete(Request $request, $id)
{
    // Buscar al usuario por ID
    $user = User::find($id);

    // Verificar si el usuario existe
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Usuario no encontrado',
        ], 404);
    }

    // Actualizar el campo aktibatua a 0 para desactivar al usuario
    $user->aktibatua = 0;
    $user->save();

    return response()->json([
        'success' => true,
        'message' => 'Usuario desactivado correctamente',
        'user' => $user
    ], 200);
}


}
