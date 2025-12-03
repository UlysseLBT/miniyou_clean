<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * GET /api/posts
     * Liste tous les posts
     */
    public function index()
    {
        // Tu peux ajouter ->with('user') si tu veux renvoyer l'auteur
        return response()->json(Post::all(), 200);
    }

    /**
     * POST /api/posts
     * Crée un nouveau post
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        $post = Post::create($data);

        return response()->json($post, 201);
    }

    /**
     * GET /api/posts/{post}
     * Affiche un post
     */
    public function show(Post $post)
    {
        return response()->json($post, 200);
    }

    /**
     * PUT/PATCH /api/posts/{post}
     * Met à jour un post
     */
    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title'   => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            // 'user_id' => 'sometimes|required|exists:users,id', // si tu veux pouvoir changer l'auteur
        ]);

        $post->update($data);

        return response()->json($post, 200);
    }

    /**
     * DELETE /api/posts/{post}
     * Supprime un post
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json(null, 204);
    }
}
