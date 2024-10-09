<?php

namespace App\Http\Controllers;

use App\Models\User; // Asegúrate de importar el modelo User
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    //
    public function index()
    {
        // Obtener todos los usuarios
        $users = User::orderBy('id', 'desc')->get();

        // Retornar los usuarios como respuesta JSON
        return response()->json($users);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
            'cargo' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validacion de datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'cargo' => $request->cargo,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['data' => $user, 'access_token' => $token, 'token_type' => 'Bearer',]);
    }

    public function show($id)
    {

        $user = User::find($id);

        if (!$user) {
            $data = [
                'message' => 'Usuario no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        };

        $data = [
            'user' => $user,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        // Buscar el usuario por ID
        $user = User::find($id);

        if (!$user) {
            $data = [
                'message' => 'Usuario no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        // Validación de datos
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id, // Email único excepto para el usuario actual
            'password' => 'nullable|min:8', // Contraseña opcional pero con mínimo de 8 caracteres
            'cargo' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en la validación de datos',
                'errors' => $validator->errors(),
                'status' => 422
            ];
            return response()->json($data, 422); // Cambia el estado a 422 (Unprocessable Entity)
        }

        // Actualizar datos del usuario
        $user->name = $request->name;
        $user->email = $request->email;

        // Solo actualizar la contraseña si es proporcionada
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password); // Hashear la contraseña
        }

        $user->cargo = $request->cargo;
        $user->apellido_paterno = $request->apellido_paterno;
        $user->apellido_materno = $request->apellido_materno;

        // Guardar los cambios
        $user->save();

        // Respuesta de éxito
        $data = [
            'message' => 'Usuario actualizado correctamente',
            'user' => $user, // Devolver el usuario actualizado
            'status' => 200
        ];

        return response()->json($data, 200); // Estado 200 para éxito
    }
    
}
