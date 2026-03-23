<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Report;
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

    // 👇 ajouté
    public function report(Request $request, Comment $comment)
    {
        if ($comment->user_id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas signaler votre propre commentaire.');
        }

        $alreadyReported = Report::where('comment_id', $comment->id)
            ->where('user_id', auth()->id())
            ->exists();

        if ($alreadyReported) {
            return back()->with('error', 'Vous avez déjà signalé ce commentaire.');
        }

        $data = $request->validate([
            'reason'  => ['required', 'in:spam,harassment,inappropriate,misinformation,other'],
            'details' => ['nullable', 'string', 'max:500'],
        ]);

        Report::create([
            'comment_id' => $comment->id,
            'post_id'    => null,
            'user_id'    => auth()->id(),
            'reason'     => $data['reason'],
            'details'    => $data['details'] ?? null,
        ]);

        return back()->with('status', 'Commentaire signalé. Merci.');
    }
}