<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Post;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;



class ProfileController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();

        // Récupère les posts de l'utilisateur
        // ->withCount(['likes','comments']) si tu as les relations likes/comments
        $posts = Post::query()
            ->where('user_id', $user->id)
            ->latest()
            ->withCount(['likes', 'comments'])
            ->get();

        return view('profile.index', compact('user', 'posts'));
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
    $user = $request->user();

    $validated = $request->validate([
        'name' => ['required','string','max:255'],
        'email' => [
            'required','string','lowercase','email','max:255',
            Rule::unique('users', 'email')->ignore($user->id),
        ],

        'bio' => ['nullable','string','max:1000'],
        'website' => ['nullable','string','max:255'],
        'twitter' => ['nullable','string','max:255'],
        'instagram' => ['nullable','string','max:255'],

        // ✅ AVATAR
        'avatar' => ['nullable','file','image','mimes:jpg,jpeg,png,webp','max:4096'],
    ]);

    // ✅ Upload avatar si fourni
    if ($request->hasFile('avatar')) {
        // supprime l'ancien si présent (et si c'est bien un fichier local)
        if ($user->avatar_path && !str_starts_with($user->avatar_path, 'http')) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $validated['avatar_path'] = $path;
    }

    // si l'email a changé, force une re-vérification
    if ($validated['email'] !== $user->email) {
        $user->email_verified_at = null;
    }

    $user->fill($validated)->save();

    return back()->with('status', 'profile-updated');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
