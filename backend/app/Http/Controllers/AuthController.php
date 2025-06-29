<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais estão incorretas.'],
            ]);
        }

        $user->tokens()->delete();

        $token = $user->createToken($user->name)->plainTextToken;

        return response([
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ], 200);

    }

    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::create($fields);

        $token = $user->createToken($request->name);

        return response([
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response([
            'message' => 'Usuário desconectado.'
        ], 200);
    }
}
