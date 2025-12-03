<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * GET /api/users
     * Liste de tous les utilisateurs (sans mot de passe)
     */
    public function index()
    {
        $users = User::select(
            'id',
            'name',
            'username',
            'email',
            'display_name',
            'avatar_path',
            'bio',
            'website',
            'twitter',
            'instagram',
            'created_at',
            'updated_at'
        )->get();

        return response()->json($users, 200);
    }

    /**
     * POST /api/users
     * Crée un nouvel utilisateur
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'username'      => 'required|string|max:255|unique:users,username',
            'email'         => 'required|email|max:255|unique:users,email',
            'password'      => 'required|string|min:8',
            'display_name'  => 'nullable|string|max:255',
            'avatar_path'   => 'nullable|string|max:255',
            'bio'           => 'nullable|string|max:2000',
            'website'       => 'nullable|url|max:255',
            'twitter'       => 'nullable|string|max:255',
            'instagram'     => 'nullable|string|max:255',
        ]);

        // PAS de Hash::make ici, le cast 'password' => 'hashed' s'en charge
        $user = User::create($data);

        return response()->json($user, 201);
    }

    /**
     * GET /api/users/{user}
     * Affiche un utilisateur
     */
    public function show(User $user)
    {
        // password et remember_token sont déjà masqués par $hidden
        return response()->json($user, 200);
    }

    /**
     * PUT/PATCH /api/users/{user}
     * Met à jour un utilisateur
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'          => 'sometimes|required|string|max:255',
            'username'      => 'sometimes|required|string|max:255|unique:users,username,' . $user->id,
            'email'         => 'sometimes|required|email|max:255|unique:users,email,' . $user->id,
            'password'      => 'sometimes|required|string|min:8',
            'display_name'  => 'nullable|string|max:255',
            'avatar_path'   => 'nullable|string|max:255',
            'bio'           => 'nullable|string|max:2000',
            'website'       => 'nullable|url|max:255',
            'twitter'       => 'nullable|string|max:255',
            'instagram'     => 'nullable|string|max:255',
        ]);

        // Toujours pas de Hash::make : le cast s'en charge
        $user->update($data);

        return response()->json($user, 200);
    }

    /**
     * DELETE /api/users/{user}
     * Supprime un utilisateur
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(null, 204);
    }
}
