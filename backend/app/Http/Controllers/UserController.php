<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('profiles')->get();

        return response()->json($users->map(function ($user) {
            return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            'profiles' => $user->profiles->map(fn($profile) => [
                'id' => $profile->id,
                'name' => $profile->name,
                'description' => $profile->description,
            ]),
            ];
        }));
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = User::create($fields);

        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at,
            'updated_at'=> $user->updated_at,
            'profiles' => $user->profiles->map(fn($profile) => [
                'id' => $profile->id,
                'name' => $profile->name,
                'description' => $profile->description,
            ]),
        ], 200);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|unique:users,email,' . $user->id,
            'password'=> 'sometimes|string|min:6|confirmed',
        ]);

        $user->update($validated);

        return response()->json($user, 200);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['message' => 'Usuário excluído.'], 204);
    }
}
