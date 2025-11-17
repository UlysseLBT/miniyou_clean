<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        // Si tu veux voir uniquement tes posts :
        // $posts = Post::where('user_id', auth()->id())->latest()->paginate(10);

        // Si tu veux un vrai "fil" avec tous les posts :
        $posts = Post::with('user')
            ->latest()
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        // Validation des champs du formulaire
        $data = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'texte' => ['nullable', 'string'],
            'url'   => ['required', 'string', 'max:2048', 'url'],
        ]);

        // Création du post (pas de fichier, juste texte + lien)
        Post::create([
            'user_id' => $request->user()->id,
            'titre'   => $data['titre'],
            'texte'   => $data['texte'] ?? null,
            'url'     => $data['url'],
        ]);

        return redirect()
            ->route('posts.index')
            ->with('status', 'Post créé');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        // Plus de fichier à supprimer, juste le post
        $post->delete();

        return redirect()
            ->route('posts.index')
            ->with('status', 'Post supprimé');
    }
}
