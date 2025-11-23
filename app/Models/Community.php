<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'description',
        'visibility',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps()
            ->withPivot('role');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function create(Request $request, Community $community = null)
    {   
    // $community sera renseignée si on vient de /communities/{community}/posts/create
    return view('posts.create', [
        'community' => $community,
    ]);
    }
public function store(Request $request)
    {
    $data = $request->validate([
        'titre'        => ['required', 'string', 'max:255'],
        'texte'        => ['nullable', 'string'],
        'url'          => ['required', 'string', 'max:2048', 'url'],
        'community_id' => ['nullable', 'exists:communities,id'],
    ]);

    $post = Post::create([
        'user_id'      => $request->user()->id,
        'community_id' => $data['community_id'] ?? null,
        'titre'        => $data['titre'],
        'texte'        => $data['texte'] ?? null,
        'url'          => $data['url'],
    ]);

    // Si le post est dans une communauté, on retourne vers la communauté
    if (!empty($data['community_id'])) {
        return redirect()
            ->route('communities.show', $data['community_id'])
            ->with('status', 'Post créé dans la communauté.');
    }

    return redirect()
        ->route('posts.index')
        ->with('status', 'Post créé.');
}
}