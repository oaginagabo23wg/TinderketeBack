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
use Illuminate\Support\Facades\DB;

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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'birth_date' => 'required|date|before:-18 years',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $imagePath = '1361728.png';  

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'birth_date' => Carbon::parse($request->birth_date),
            'admin' => '0',
            'hometown' => $request->hometown ?? null,
            'telephone' => $request->telephone ?? null,
            'img' => $imagePath,
            'aktibatua' => '0',
        ]);

        Mail::to($user->email)->send(new UserCreatedMail($user));

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Erabiltzailea ongi sortu da',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|max:255',
            'hometown' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:15',
            'birth_date' => 'required|date',
            'admin' => 'required|boolean',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'aktibatua' => 'required|boolean',
        ]);

        if ($request->hasFile('img')) {
            $path = $request->file('img')->store('', 'public');
            $validated['img'] = $path;
        }

        $user = User::create($validated);

        return response()->json([
            'success' => true,
            'data' => $user,
        ], 201);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Validación de la imagen
        ]);

        // Guardar la imagen en la carpeta 'public' dentro de 'storage/app'
        $imagePath = $request->file('image')->store('images', 'public');

        // Devolver el path de la imagen
        return response()->json(['imagePath' => $imagePath]);
    }
    

    public function update(Request $request, string $id)
    {
        $erabil = User::find($id);
        
        if (!$erabil) {
            return response()->json([
                'success' => false,
                'message' => 'Erabiltzailea ez da aurkitu'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'surname' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8|confirmed',
            'birth_date' => 'sometimes|date|before:-18 years',
            'admin' => 'sometimes|boolean',
            'hometown' => 'sometimes|string|nullable',
            'telephone' => 'sometimes|string|nullable',
            'img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
            'aktibatua' => 'sometimes|boolean',
        ]);

        if ($request->hasFile('img')) {
            $path = $request->file('img')->store('', 'public');
            $validated['img'] = $path;
        } else {
            // Keep existing image path if no new image is uploaded
            $validated['img'] = $erabil->img;
        }

        $erabil->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Erabiltzailea ongi aldatu da.',
            'data' => $erabil
        ], 200);
    }


    public function sendEmail(Request $request)
    {
        // Validar los datos que vienen del frontend
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
            'user_id' => 'nullable|integer|exists:users,id',  // Validación opcional de user_id
        ]);

        // Crear el arreglo de datos que se enviará al correo
        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'message' => $validated['message'],
        ];

        // Si el usuario está logueado, agregar su user_id
        if (isset($validated['user_id'])) {
            $data['user_id'] = $validated['user_id'];
        }

        // Enviar el correo
        try {
            Mail::to('tinderkete@gmail.com') // Cambiar a la dirección de destino
                ->send(new ContactMail($data));
            return response()->json(['message' => 'Mezua ongi biali da'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Errorea mezua bialtzerakoan', 'error' => $e->getMessage()], 500);
        }
    }


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
                'message' => 'Kredentzialak ez dira baliozkoak.',
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
                'message' => 'Erabiltzailea ez da aurkitu',
            ], 401);
        }

        // Genera la URL pública de la imagen
        $imageUrl = asset('storage/' . $user->img); // Asegúrate de que storage:link esté creado

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
                'message' => 'Ez da erabiltzailea aurkitu',
            ], 404);
        }

        // Actualizar el campo aktibatua a 0 para desactivar al usuario
        $user->aktibatua = 0;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Erabiltzailea ongi desaktibatuta',
            'user' => $user
        ], 200);
    }

        public function activateUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Erabiltzailea ez da aurkitu'], 404);
        }

        // Si ya está activado, redirigir directamente a login
        if ($user->aktibatua == 1) {
            return redirect(env('APP_URL') . ':3000/login')->with('message', 'Zure kontua dagoeneko aktibatuta dago!');
        }

        // Activar usuario
        $user->aktibatua = 1;
        $user->save();

        // Redirigir a la página de login
        return redirect(env('APP_URL') . ':3000/login')->with('message', 'Zure kontua aktibatu da!');
    }
}
