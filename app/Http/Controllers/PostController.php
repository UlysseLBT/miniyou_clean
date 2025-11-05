<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = \App\Models\Post::with('user')->latest()->paginate(10);
        return view('posts.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre' => ['required','string','max:255'],
            'texte' => ['nullable','string','max:2000'],
            'media' => ['required','file','max:10240'], // 10MB, obligatoire car media_url NOT NULL
        ]);

        $file = $request->file('media');
        $path = $file->store('post', 'public');

        Post::create([
            'user_id'             => $request->user()->id,
            'titre'               => $data['titre'],
            'texte'               => $data['texte'] ?? null,
            'media_disk'          => 'public',
            'media_url'           => $path, // ex: posts/abc.jpg
            'media_mime'          => $file->getClientMimeType(),
            'media_size'          => $file->getSize(),
            'media_original_name' => $file->getClientOriginalName(),
        ]);

        return back()->with('status', 'Post publié ✅');
    }

    public function destroy(Post $post)
    {
        abort_unless($post->user_id === auth()->id(), 403);

        if ($post->media_url && $post->media_disk === 'public') {
            \Storage::disk('public')->delete($post->media_url);
        }
        $post->delete();

        return back()->with('status', 'Post supprimé');
    }
}
