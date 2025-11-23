<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostLikeController extends Controller
{
    public function toggle(Post $post, Request $request)
    {
        $user = $request->user();

        $alreadyLiked = $post->likes()->where('user_id', $user->id)->exists();

        if ($alreadyLiked) {
            $post->likes()->where('user_id', $user->id)->delete();
            $message = 'Like retiré.';
        } else {
            $post->likes()->create(['user_id' => $user->id]);
            $message = 'Post liké.';
        }

        return back()->with('status', $message);
    }
}
