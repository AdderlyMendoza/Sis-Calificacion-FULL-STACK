<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
// use Validator;
Use App\Models\User;
use \stdClass;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email'=> $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['data'=>$user, 'access_token' => $token, 'token_type' => 'Bearer',]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password')))
        {
            return response()->json(['message' => 'Sin autorizacion'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Hi '.$user->name,
            'accessToken' => $token,
            'token_type' => 'Bearer',
            // 'user' => $user,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }    

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return ['message' => 'Logout exitoso, token eliminado'];
    }



}
