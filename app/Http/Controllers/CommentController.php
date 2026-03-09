<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Notification;
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

        // Notif seulement si ce n'est pas son propre post
        if ($post->user_id !== $request->user()->id) {
            Notification::create([
                'user_id' => $post->user_id,
                'type'    => 'post_commented',
                'data'    => [
                    'commenter_name' => $request->user()->name,
                    'post_titre'     => $post->titre,
                    'url'            => route('posts.show', $post->id),
                ],
            ]);
        }

        return back()->with('status', 'Commentaire ajouté.');
    }

    public function destroy(Comment $comment)
    {
        if (auth()->id() !== $comment->user_id) {
            abort(403);
        }

        $comment->delete();

        return back()->with('status', 'Commentaire supprimé.');
    }
}