<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Profile::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required|string|max:255|unique:profiles',
            'description' => 'string|max:255'
        ]);

        $profile = Profile::create($validate);

        return response()->json($profile, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Profile $profile)
    {
        return response()->json($profile, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Profile $profile)
    {
        $validate = $request->validate([
            'name'=> 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:255'
        ]);
        $profile->update($validate);

        return response()->json($profile, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profile $profile)
    {
        $profile->delete();

        return response()->json([
            'message'=> 'Perfil deletado'
        ], 204);
    }
}
