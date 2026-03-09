<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = trim($request->get('q', ''));

        $users = User::query()
            ->when($query, fn($q) => $q
                ->where('name', 'like', "%{$query}%")
                ->orWhere('username', 'like', "%{$query}%")
                ->orWhere('display_name', 'like', "%{$query}%")
            )
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        // Suggestions : users non suivis, triés par nb de followers
        $suggestions = collect();
        if (!$query && auth()->check()) {
            $followingIds = auth()->user()->following()->pluck('users.id')->push(auth()->id());
            $suggestions = User::withCount('followers')
                ->whereNotIn('id', $followingIds)
                ->orderByDesc('followers_count')
                ->limit(6)
                ->get();
        }

        return view('users.index', compact('users', 'query', 'suggestions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password')); 
        $user->save();
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::withCount(['followers', 'following', 'posts'])->findOrFail($id);

        $posts = $user->posts()
            ->withCount(['comments', 'likes as likes_count'])
            ->latest()
            ->get();

        $isFollowing = auth()->check() && auth()->user()->isFollowing($user);

        return view('users.show', compact('user', 'posts', 'isFollowing'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->save();
        return redirect()->route('users.show', $user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) :  RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index');
    }
    public function posts()
    {
    return $this->hasMany(Post::class);
    }
}