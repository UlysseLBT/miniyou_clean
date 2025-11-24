<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;
use App\Models\User;

class ProfileController extends Controller
{
       public function index(Request $request)
    {
    $user = $request->user();

    $posts = $user->posts()
        ->with('community')
        ->withCount(['comments', 'likes'])
        ->latest()
        ->get();

    return view('profile.index', [
        'user'  => $user,
        'posts' => $posts,
    ]);
    }


    public function edit(Request $request)
    {
        $user = $request->user();
        return view('profile.edit', compact('user'));
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

        // 2. Mise à jour de l'objet utilisateur
        $user->fill($data);
        $user->save();

        return back()->with('status', 'Profil mis à jour ✅');
    }
}
