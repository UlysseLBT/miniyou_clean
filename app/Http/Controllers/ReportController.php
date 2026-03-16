<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'reason' => ['required', 'in:spam,inappropriate,harassment,misinformation'],
        ]);

        // Vérifie si l'user a déjà signalé ce post
        $already = Report::where('user_id', auth()->id())
            ->where('post_id', $post->id)
            ->exists();

        if ($already) {
            return back()->with('error', 'Tu as déjà signalé ce post.');
        }

        // Empêche de signaler son propre post
        if ($post->user_id === auth()->id()) {
            return back()->with('error', 'Tu ne peux pas signaler ton propre post.');
        }

        Report::create([
            'user_id' => auth()->id(),
            'post_id' => $post->id,
            'reason'  => $request->reason,
        ]);

        return back()->with('status', 'Post signalé. Merci pour ta contribution.');
    }
}