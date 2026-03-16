<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\Community;
use App\Services\HashtagService;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'hashtags'])
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

    public function store(Request $request, HashtagService $hashtagService)
    {
        $data = $request->validate([
            'titre'        => ['required', 'string', 'max:255'],
            'texte'        => ['nullable', 'string'],
            'url'          => ['nullable', 'string', 'max:2048', 'url'],
            'community_id' => ['nullable', 'exists:communities,id'],
            'tags'         => ['nullable', 'string', 'max:200'], // 👈 ajouté
        ]);

        $post = Post::create([
            'user_id'      => $request->user()->id,
            'community_id' => $data['community_id'] ?? null,
            'titre'        => $data['titre'],
            'texte'        => $data['texte'] ?? null,
            'url'          => $data['url'] ?? null,
        ]);

        // 👇 Sync hashtags (contenu + champ dédié)
        $hashtagService->syncHashtags(
            $post,
            ($data['texte'] ?? '') . ' ' . ($data['titre'] ?? ''),
            $data['tags'] ?? null
        );

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

    public function show(Post $post, Request $request)
    {
        $page = $request->query('page', 1);

        // 👇 Chargement des hashtags pour la page de détail
        $post->load('hashtags');

        return view('posts.show', [
            'post' => $post,
            'page' => $page,
        ]);
    }
}