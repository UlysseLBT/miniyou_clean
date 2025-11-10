<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class PostController extends Controller
{
    public function index()
    {  
        $posts = Post::where('user_id', auth()->id())->latest()->paginate(10);
        return view('post.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'content' => ['required','string','max:280'],
            'media' => ['nullable','file','max:5120']
        ]);

        $mediaUrl = null;
        $mediaDisk = null;

        if (isset($data['media'])) {
            $file = $data['media'];
            $mediaUrl = $file->store('posts','public');
            $mediaDisk = 'public';
        }

        Post::create([
            'user_id' => auth()->id(),
            'content' => $data['content'],
            'media_url' => $mediaUrl,
            'media_disk' => $mediaDisk,
        ]);

        return back()->with('status', 'Post créé');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        if ($post->media_url) {
            \Storage::disk($post->media_disk)->delete($post->media_url);
        }

        $post->delete();

        return back()->with('status', 'Post supprimé');
    }
}
