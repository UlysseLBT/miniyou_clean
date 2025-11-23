<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);

        $post->comments()->create([
            'user_id' => $request->user()->id,
            'body'    => $data['body'],
        ]);

        return back()->with('status', 'Commentaire ajouté.');
    }

public function destroy(Comment $comment)
    {
    // On vérifie simplement que l'utilisateur est bien l'auteur du commentaire
    if (auth()->id() !== $comment->user_id) {
        abort(403);
    }

    $comment->delete();

    return back()->with('status', 'Commentaire supprimé.');
    }
}