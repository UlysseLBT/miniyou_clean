<?php

namespace App\Http\Controllers;

use App\Models\Hashtag;

class HashtagController extends Controller
{
    public function show(string $name)
    {
        $hashtag = Hashtag::where('name', strtolower($name))
            ->firstOrFail();

        $posts = $hashtag->posts()
            ->with(['user', 'hashtags'])
            ->latest()
            ->paginate(15);

        return view('hashtags.show', compact('hashtag', 'posts'));
    }
}