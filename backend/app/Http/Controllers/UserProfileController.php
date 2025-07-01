<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Profile;

use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function addProfile(Request $request, User $user) {
        try {
            $validated = $request->validate([
                'profile_id' => 'required|exists:profiles,id',
            ]);

            $profileId = $validated['profile_id'];

            if ($user->profiles()->where('profile_id', $profileId)->exists()) {
                return response()->json([
                    'message' => 'Usuário já possui este perfil.',
                    'user_id' => $user->id,
                    'profile_id' => $profileId
                ], 400);
            }

            $user->profiles()->attach($profileId);

            return response()->json([
                'message' => 'Perfil adicionado com sucesso.',
                'user_id' => $user->id,
                'profile_id' => $profileId
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Dados inválidos.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro interno do servidor.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function removeProfile(Request $request, User $user, Profile $profile) {
        $profileId = $profile->id;

        // Verificar se o usuário realmente possui o perfil antes de tentar remover
        if (!$user->hasProfileById($profileId)) {
            return response()->json([
                'message' => 'Usuário não possui este perfil.',
                'error' => 'PROFILE_NOT_FOUND'
            ], 400);
        }

        $user->profiles()->detach(['profile_id' => $profileId]);

        return response()->json([
            'message' => 'Perfil removido com sucesso.',
            'user_id' => $user->id,
            'profile_id' => $profileId
        ], 200);
    }

    /**
     * Listar todos os perfis do usuário
     */
    public function getUserProfiles(User $user) {
        $profiles = $user->profiles()->get();

        return response()->json([
            'user_id' => $user->id,
            'profiles' => $profiles
        ], 200);
    }

    /**
     * Verificar se o usuário possui um perfil específico
     */
    public function checkUserProfile(Request $request, User $user) {
        $validated = $request->validate([
            'profile_id' => 'required|exists:profiles,id',
        ]);

        $profileId = $validated['profile_id'];
        $hasProfile = $user->hasProfileById($profileId);

        return response()->json([
            'user_id' => $user->id,
            'profile_id' => $profileId,
            'has_profile' => $hasProfile
        ], 200);
    }
}
