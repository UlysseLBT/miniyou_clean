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
        'url'          => ['required', 'string', 'max:2048', 'url'],
        'community_id' => ['required', 'exists:communities,id'],
    ]);

    $post = Post::create([
        'user_id'      => $request->user()->id,
        'community_id' => $data['community_id'],
        'titre'        => $data['titre'],
        'texte'        => $data['texte'] ?? null,
        'url'          => $data['url'],
    ]);

    return redirect()
        ->route('communities.show', $data['community_id'])
        ->with('status', 'Post créé dans la communauté.');
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

public function show(Post $post, Request $request)
{
    $page = $request->query('page', 1);

    return view('posts.show', [
        'post' => $post,
        'page' => $page,
    ]);
}
}