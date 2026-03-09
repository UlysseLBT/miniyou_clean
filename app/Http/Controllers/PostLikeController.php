<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Notification;
use Illuminate\Http\Request;

class PostLikeController extends Controller
{
    public function toggle(Post $post, Request $request)
    {
        $user = $request->user();
        $alreadyLiked = $post->likes()->where('user_id', $user->id)->exists();

        if ($alreadyLiked) {
            $post->likes()->where('user_id', $user->id)->delete();
        } else {
            $post->likes()->create(['user_id' => $user->id]);

            // Notif seulement si ce n'est pas son propre post
            if ($post->user_id !== $user->id) {
                Notification::create([
                    'user_id' => $post->user_id,
                    'type'    => 'post_liked',
                    'data'    => [
                        'liker_name' => $user->name,
                        'post_titre' => $post->titre,
                        'url'        => route('posts.show', $post->id),
                    ],
                ]);
            }
        }

        return back();
    }
}