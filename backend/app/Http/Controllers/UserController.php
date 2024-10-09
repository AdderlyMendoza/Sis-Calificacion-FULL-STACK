<?php

namespace App\Http\Controllers;
use App\Models\User; // Asegúrate de importar el modelo User


use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index()
    {
        // Obtener todos los usuarios
        $users = User::all();
        
        // Retornar los usuarios como respuesta JSON
        return response()->json($users);
        
    }

    
}
