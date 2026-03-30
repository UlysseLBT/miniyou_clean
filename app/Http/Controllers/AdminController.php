<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // ─── STATS ───────────────────────────────────────────────────────────────────

    public function stats()
    {
        $sevenDaysAgo = now()->subDays(7);

        return response()->json([
            'total_users'     => User::count(),
            'total_posts'     => Post::count(),
            'total_reports'   => Report::count(),
            'pending_reports' => Report::where('status', 'pending')->count(),
            'banned_users'    => User::where('is_banned', true)->count(),
            'new_users_week'  => User::where('created_at', '>=', $sevenDaysAgo)->count(),
            'new_posts_week'  => Post::where('created_at', '>=', $sevenDaysAgo)->count(),
        ]);
    }

    // ─── USERS ───────────────────────────────────────────────────────────────────

    public function users(Request $request)
    {
        $query = User::withCount(['posts', 'followers', 'following']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%")
                  ->orWhere('username', 'like', "%$s%");
            });
        }

        return response()->json($query->latest()->paginate(20));
    }

    public function banUser($id)
    {
        User::findOrFail($id)->update(['is_banned' => true]);
        return response()->json(['message' => 'Utilisateur banni.']);
    }

    public function unbanUser($id)
    {
        User::findOrFail($id)->update(['is_banned' => false]);
        return response()->json(['message' => 'Utilisateur débanni.']);
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'Utilisateur supprimé.']);
    }

    public function promoteUser($id)
    {
        User::findOrFail($id)->update(['is_admin' => true]);
        return response()->json(['message' => 'Utilisateur promu administrateur.']);
    }

    // ─── POSTS ───────────────────────────────────────────────────────────────────

    public function posts(Request $request)
    {
        $query = Post::with('user')->withCount(['likes', 'comments', 'reports']);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('body', 'like', "%$s%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%$s%"));
            });
        }

        return response()->json($query->latest()->paginate(20));
    }

    public function deletePost($id)
    {
        Post::findOrFail($id)->delete();
        return response()->json(['message' => 'Post supprimé.']);
    }

    // ─── REPORTS ─────────────────────────────────────────────────────────────────

    public function reports(Request $request)
    {
        $query = Report::with(['reporter', 'post.user']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        return response()->json($query->latest()->paginate(20));
    }

    public function resolveReport($id)
    {
        Report::findOrFail($id)->update(['status' => 'resolved']);
        return response()->json(['message' => 'Signalement résolu.']);
    }

    public function dismissReport($id)
    {
        Report::findOrFail($id)->update(['status' => 'dismissed']);
        return response()->json(['message' => 'Signalement ignoré.']);
    }

    public function resolveAndDelete($id)
    {
        $report = Report::with('post')->findOrFail($id);
        $report->post?->delete();
        $report->update(['status' => 'resolved']);
        return response()->json(['message' => 'Signalement résolu et post supprimé.']);
    }
}
