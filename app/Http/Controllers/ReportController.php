<?php
// app/Http/Controllers/ReportController.php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // POST /posts/{post}/report — soumettre un signalement
    public function store(Request $request, Post $post)
    {
        // Empêche de signaler son propre post
        if ($post->user_id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas signaler votre propre post.');
        }

        // Vérifie si l'user a déjà signalé ce post
        $alreadyReported = Report::where('post_id', $post->id)
            ->where('user_id', auth()->id())
            ->exists();

        if ($alreadyReported) {
            return back()->with('error', 'Vous avez déjà signalé ce post.');
        }

        $data = $request->validate([
            'reason'  => ['required', 'in:spam,harassment,inappropriate,misinformation,other'],
            'details' => ['nullable', 'string', 'max:500'],
        ]);

        Report::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'reason'  => $data['reason'],
            'details' => $data['details'] ?? null,
        ]);

        return back()->with('status', 'Post signalé. Merci pour votre contribution.');
    }

    // GET /admin/reports — panel admin : liste des signalements
    public function index()
    {
        $reports = Report::with(['post.user', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);

        return view('admin.reports.index', compact('reports'));
    }
    // Remplace reviewed() et dismiss() par :
    public function update(Request $request, Report $report)
    {
    $data = $request->validate([
        'status' => ['required', 'in:reviewed,dismissed'],
    ]);

    $report->update(['status' => $data['status']]);

    $message = $data['status'] === 'reviewed' ? 'Signalement marqué comme traité.' : 'Signalement ignoré.';

    return back()->with('status', $message);
}

    // PATCH /admin/reports/{report}/reviewed — marquer comme traité
    public function reviewed(Report $report)
    {
        $report->update(['status' => 'reviewed']);

        return back()->with('status', 'Signalement marqué comme traité.');
    }

    // PATCH /admin/reports/{report}/dismiss — ignorer
    public function dismiss(Report $report)
    {
        $report->update(['status' => 'dismissed']);

        return back()->with('status', 'Signalement ignoré.');
    }

    // DELETE /admin/reports/{report}/delete-post — supprimer le post signalé
    public function deletePost(Report $report)
    {
        $report->post()->delete(); // cascade supprime aussi les reports liés
        
        return back()->with('status', 'Post supprimé.');
    }
}