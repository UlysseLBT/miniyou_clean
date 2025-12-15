<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\Community;

class PostController extends Controller
{
    public function index()
{
    $posts = Post::with('user')
        ->withCount(['comments', 'likes'])
        ->whereNull('community_id') 
        ->latest()
        ->paginate(10);

    return view('posts.index', compact('posts'));
}

public function create(Community $community)
{
    return view('posts.create', [
        'community' => $community,
    ]);
}

    
public function store(Request $request)
{
    $data = $request->validate([
        'titre'        => ['required', 'string', 'max:255'],
        'texte'        => ['nullable', 'string'],
        'url'          => ['nullable', 'string', 'max:2048', 'url'], // plus "required"
        'community_id' => ['nullable', 'exists:communities,id'],     // plus "required"
    ]);

    $post = Post::create([
        'user_id'      => $request->user()->id,
        'community_id' => $data['community_id'] ?? null,
        'titre'        => $data['titre'],          // 👈 maintenant on l’envoie bien
        'texte'        => $data['texte'] ?? null,  // 👈 idem
        'url'          => $data['url'] ?? null,
    ]);

    if ($post->community_id) {
        return redirect()
            ->route('communities.show', $post->community_id)
            ->with('status', 'Post créé dans la communauté.');
    }

    return redirect()
        ->route('posts.index')
        ->with('status', 'Post créé.');
}


public function destroy(Post $post)
{
    if ($post->user_id !== auth()->id()) {
        abort(403);
    }

    $post->delete();

    return redirect()
        ->route('posts.index')
        ->with('status', 'Post supprimé');
}

public function show(Post $post)
{
    $post->load(['user', 'comments.user', 'likes']);
    $post->loadCount(['comments', 'likes']);

    return view('posts.show', compact('post'));
}
}