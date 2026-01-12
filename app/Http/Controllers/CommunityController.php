<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CommunityController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth', only: ['create', 'store', 'join', 'leave', 'destroy']),
        ];
    }

    /**
     * Liste des communautés
     * - Visiteur : seulement public
     * - Connecté : toutes (public + private) => la page show gère la “porte” (demande)
     */
    public function index()
    {
        $userId = auth()->id();

        $communities = Community::query()
            ->when(!$userId, fn ($q) => $q->where('visibility', 'public'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('communities.index', compact('communities'));
    }

    public function create()
    {
        return view('communities.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'visibility'  => ['required', 'in:public,private'],
        ]);

        $community = Community::create([
            'owner_id'    => $request->user()->id,
            'name'        => $data['name'],
            'slug'        => Str::slug($data['name']) . '-' . uniqid(),
            'description' => $data['description'] ?? null,
            'visibility'  => $data['visibility'],
        ]);

        // Le créateur devient membre owner
        $community->members()->syncWithoutDetaching([
            $request->user()->id => ['role' => 'owner'],
        ]);

        return redirect()
            ->route('communities.show', $community)
            ->with('status', 'Communauté créée avec succès.');
    }

    /**
     * Afficher une communauté
     * - Private + non membre => page communities.private (demande d’accès)
     * - Owner => voit aussi la liste des demandes pending
     */
    public function show(Community $community)
    {
        $community->load(['owner']);

        // Communauté privée => si pas membre => page "demande d'accès"
        if ($community->visibility === 'private') {
            $user = auth()->user();

            if (!$user) {
                return redirect()->guest(route('login'));
            }

            $isMember = $community->owner_id === $user->id
                || $community->members()->where('users.id', $user->id)->exists();

            if (!$isMember) {
                $pending = $community->joinRequests()
                    ->where('user_id', $user->id)
                    ->where('status', 'pending')
                    ->first();

                return view('communities.private', [
                    'community' => $community,
                    'pending'   => $pending,
                ]);
            }
        }

        // Ici : soit public, soit membre/owner
        $community->load(['members']);

        // ✅ Liste des demandes en attente (uniquement propriétaire)
        $pendingRequests = collect();
        if (auth()->check() && auth()->id() === $community->owner_id) {
            $pendingRequests = $community->joinRequests()
                ->with('user')
                ->where('status', 'pending')
                ->latest()
                ->get();
        }

        $posts = $community->posts()
            ->with('user')
            ->withCount(['comments', 'likes'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('communities.show', [
            'community'       => $community,
            'posts'           => $posts,
            'pendingRequests' => $pendingRequests,
        ]);
    }

    /**
     * Rejoindre une communauté publique
     */
    public function join(Community $community)
    {
        if ($community->visibility === 'private') {
            abort(403, "Cette communauté est privée (demande/invitation requise).");
        }

        $user = request()->user();

        $community->members()->syncWithoutDetaching([
            $user->id => ['role' => 'member'],
        ]);

        return back()->with('status', 'Vous avez rejoint la communauté.');
    }

    public function leave(Community $community)
    {
        $user = request()->user();

        if ($community->owner_id === $user->id) {
            return back()->with('status', "Le propriétaire ne peut pas quitter sa communauté.");
        }

        $community->members()->detach($user->id);

        return back()->with('status', 'Vous avez quitté la communauté.');
    }

    public function destroy(Community $community)
    {
        $user = request()->user();

        abort_unless($community->owner_id === $user->id, 403);

        $community->posts()->delete();
        $community->members()->detach();
        $community->delete();

        return redirect()
            ->route('communities.index')
            ->with('status', 'Communauté supprimée avec succès.');
    }
}
