<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'display_name' => ['nullable','string','max:255'],
            'bio'          => ['nullable','string','max:2000'],
            'website'      => ['nullable','url','max:255'],
            'twitter'      => ['nullable','string','max:255'],
            'instagram'    => ['nullable','string','max:255'],
            'avatar'       => ['nullable','image','max:2048'],
        ]);

        // Upload avatar si présent
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');

            // Supprime l’ancien fichier si besoin
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }

            $data['avatar_path'] = $path;
        }

        $user->update($data);

        return back()->with('status', 'Profil mis à jour ✅');
    }
}
