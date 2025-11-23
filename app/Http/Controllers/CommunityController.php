<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CommunityController extends Controller
{
    /**
     * Liste des communautés
     */
    public function index()
    {
        $communities = Community::with('owner')->latest()->paginate(12);

        return view('communities.index', compact('communities'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return view('communities.create');
    }

    /**
     * Enregistrer une nouvelle communauté
     */
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

        // le créateur devient membre "owner"
        $community->members()->attach($request->user()->id, ['role' => 'owner']);

        return redirect()->route('communities.show', $community)
            ->with('status', 'Communauté créée avec succès.');
    }

    /**
     * Afficher une communauté
     */
    public function show(Community $community)
    {
        $community->load(['owner', 'members', 'posts.user']);

        return view('communities.show', compact('community'));
    }

    /**
     * Rejoindre une communauté
     */
    public function join(Community $community)
    {
        $user = request()->user();

        if (! $community->members->contains($user->id)) {
            $community->members()->attach($user->id, ['role' => 'member']);
        }

        return back()->with('status', 'Vous avez rejoint la communauté.');
    }

    /**
     * Quitter une communauté
     */
    public function leave(Community $community)
    {
        $user = request()->user();

        $community->members()->detach($user->id);

        return back()->with('status', 'Vous avez quitté la communauté.');
    }
    public function destroy(Community $community)
    {
    $user = request()->user();

    // Seul le créateur peut supprimer
    if ($community->owner_id !== $user->id) {
        abort(403);
    }

    // Supprimer les posts de la communauté (ou les détacher si tu préfères)
    $community->posts()->delete();

    // Détacher les membres
    $community->members()->detach();

    // Supprimer la communauté
    $community->delete();

    return redirect()
        ->route('communities.index')
        ->with('status', 'Communauté supprimée avec succès.');
    }
}