<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function addProfile(Request $request, User $user) {
        $validated = $request->validate([
            'profile_id' => 'required|exists:profiles,id',
        ]);

        $profileId = $validated['profile_id'];

        $user->profiles()->syncWithoutDetaching([$profileId]);
    }

    public function removeProfile(Request $request, User $user) {
        $validated = $request->validate([
            'profile_id' => 'required|exists:profiles,id',
        ]);

        $profileId = $validated['profile_id'];

        $user->profiles()->detach($profileId);

        return response()->json(['message' => 'Perfil removido com sucesso.'], 200);
    }
}
