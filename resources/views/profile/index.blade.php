@php
    use Illuminate\Support\Str;

    $displayName = $user->display_name ?? $user->name;
    $initial     = Str::upper(Str::substr($displayName, 0, 1));

    $totalPosts    = $posts->count();
    $totalLikes    = (int) $posts->sum('likes_count');
    $totalComments = (int) $posts->sum('comments_count');

    // URL avatar (on teste plusieurs colonnes possibles)
    $avatarUrl = null;
    if (!empty($user->avatar_path)) {
        $avatarUrl = asset('storage/'.$user->avatar_path);
    } elseif (!empty($user->avatar)) {
        $avatarUrl = asset('storage/'.$user->avatar);
    } elseif (!empty($user->profile_photo_path ?? null)) {
        $avatarUrl = \Illuminate\Support\Facades\Storage::url($user->profile_photo_path);
    }
@endphp

<x-app-layout>
    <x-slot name="header"></x-slot>

    {{-- ✅ Fond + glows comme l'accueil/login/register --}}
    <div class="min-h-screen relative overflow-hidden bg-[#050506] text-neutral-100">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0">
            <div class="absolute -top-44 -left-44 h-[520px] w-[520px] rounded-full bg-red-900/35 blur-[130px]"></div>
            <div class="absolute -top-44 -right-44 h-[520px] w-[520px] rounded-full bg-amber-900/18 blur-[130px]"></div>
            <div class="absolute -bottom-64 left-1/3 h-[640px] w-[640px] rounded-full bg-indigo-900/22 blur-[150px]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,rgba(255,255,255,0.08),transparent_55%)]"></div>
        </div>

        <div class="relative py-10">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

                {{-- ✅ BANDEAU PROFIL (glass) --}}
                <div class="rounded-3xl border border-white/10 bg-neutral-950/35 backdrop-blur
                            shadow-[0_10px_35px_rgba(0,0,0,.35)] px-6 py-6 sm:px-8 sm:py-7">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">

                        {{-- Gauche : avatar + infos --}}
                        <div class="flex flex-col gap-4">
                            <div class="flex items-center gap-4">
                                @if($avatarUrl)
                                    <img src="{{ $avatarUrl }}"
                                         alt="Avatar"
                                         class="h-16 w-16 rounded-full object-cover border border-white/15">
                                @else
                                    <div class="h-16 w-16 rounded-full bg-white/5 border border-white/10
                                                flex items-center justify-center text-xl font-semibold">
                                        {{ $initial }}
                                    </div>
                                @endif

                                <div>
                                    <h1 class="text-xl font-semibold text-white">
                                        {{ $displayName }}
                                    </h1>
                                    <p class="text-sm text-neutral-400">
                                        {{ $user->email }}
                                    </p>
                                </div>
                            </div>

                            {{-- Bio --}}
                            @if(!empty($user->bio))
                                <p class="text-sm text-neutral-200/90 max-w-xl">
                                    {{ $user->bio }}
                                </p>
                            @endif

                            {{-- Liens sociaux --}}
                            <div class="flex flex-wrap items-center gap-2 text-xs">
                                @if(!empty($user->website))
                                    <a href="{{ $user->website }}" target="_blank"
                                       class="inline-flex items-center px-2.5 py-0.5 rounded-full
                                              bg-white/5 border border-white/10 text-neutral-200
                                              hover:bg-white/10 transition">
                                        🌐 <span class="ml-1 truncate max-w-[180px]">{{ $user->website }}</span>
                                    </a>
                                @endif

                                @if(!empty($user->twitter))
                                    <a href="{{ Str::startsWith($user->twitter, ['http://','https://']) ? $user->twitter : 'https://twitter.com/'.ltrim($user->twitter, '@') }}"
                                       target="_blank"
                                       class="inline-flex items-center px-2.5 py-0.5 rounded-full
                                              bg-white/5 border border-white/10 text-neutral-200
                                              hover:bg-white/10 transition">
                                        𝕏 <span class="ml-1">{{ ltrim($user->twitter, '@') }}</span>
                                    </a>
                                @endif

                                @if(!empty($user->instagram))
                                    <a href="{{ Str::startsWith($user->instagram, ['http://','https://']) ? $user->instagram : 'https://instagram.com/'.ltrim($user->instagram, '@') }}"
                                       target="_blank"
                                       class="inline-flex items-center px-2.5 py-0.5 rounded-full
                                              bg-white/5 border border-white/10 text-neutral-200
                                              hover:bg-white/10 transition">
                                        📸 <span class="ml-1">{{ ltrim($user->instagram, '@') }}</span>
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Droite : bouton + stats --}}
                        <div class="flex flex-col items-end gap-4">
                            <a href="{{ route('profile.edit') }}"
                               class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium
                                      bg-white/5 border border-red-500/40 text-white
                                      hover:bg-white/10 transition
                                      shadow-[0_0_0_1px_rgba(239,68,68,.20)]">
                                Modifier mon profil
                            </a>

                            <div class="flex flex-row sm:flex-col gap-4 text-sm">
                                <div class="text-center sm:text-right">
                                    <div class="text-lg font-semibold text-white">{{ $totalPosts }}</div>
                                    <div class="text-xs uppercase tracking-wide text-neutral-400">
                                        Post{{ $totalPosts > 1 ? 's' : '' }}
                                    </div>
                                </div>

                                <div class="text-center sm:text-right">
                                    <div class="text-lg font-semibold text-white">{{ $totalComments }}</div>
                                    <div class="text-xs uppercase tracking-wide text-neutral-400">
                                        Commentaires
                                    </div>
                                </div>

                                <div class="text-center sm:text-right">
                                    <div class="text-lg font-semibold text-white">{{ $totalLikes }}</div>
                                    <div class="text-xs uppercase tracking-wide text-neutral-400">
                                        Likes
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ✅ MES POSTS (glass) --}}
                <div class="rounded-3xl border border-white/10 bg-neutral-950/35 backdrop-blur
                            shadow-[0_10px_35px_rgba(0,0,0,.35)] p-6 sm:p-7">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-white">
                            Mes posts
                        </h3>
                        <a href="{{ route('posts.index') }}"
                           class="text-xs text-red-300 hover:text-red-200 hover:underline">
                            Voir le fil complet →
                        </a>
                    </div>

                    @if($posts->count())
                        <div class="space-y-4">
                            @foreach($posts as $post)
                                <article
                                    onclick="window.location='{{ route('posts.show', $post) }}'"
                                    class="cursor-pointer rounded-2xl border border-white/10 bg-white/5
                                           p-4 hover:bg-white/10 hover:border-white/20 hover:-translate-y-0.5 transition">

                                    <div class="flex items-start gap-3">
                                        {{-- mini avatar --}}
                                        @if($avatarUrl)
                                            <img src="{{ $avatarUrl }}"
                                                 alt="Avatar"
                                                 class="hidden sm:block h-9 w-9 rounded-full object-cover border border-white/10">
                                        @else
                                            <div class="hidden sm:flex h-9 w-9 rounded-full bg-white/5 border border-white/10
                                                        items-center justify-center text-sm font-semibold text-neutral-200">
                                                {{ $initial }}
                                            </div>
                                        @endif

                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-baseline justify-between gap-2">
                                                <h4 class="font-semibold text-white text-sm sm:text-base">
                                                    {{ $post->titre }}
                                                </h4>
                                                <span class="text-xs text-neutral-400">
                                                    {{ $post->created_at->diffForHumans() }}
                                                </span>
                                            </div>

                                            @if($post->texte)
                                                <p class="mt-1 text-xs sm:text-sm text-neutral-300">
                                                    {{ Str::limit($post->texte, 160) }}
                                                </p>
                                            @endif

                                            <div class="mt-2 flex flex-wrap items-center gap-3 text-[11px] text-neutral-400">
                                                @if($post->url)
                                                    <span class="truncate max-w-xs text-red-300">
                                                        🔗 {{ $post->url }}
                                                    </span>
                                                @endif

                                                @if($post->community)
                                                    <span class="inline-flex items-center rounded-full bg-white/5 border border-white/10 px-2 py-0.5 text-[10px] font-medium text-neutral-200">
                                                        {{ $post->community->name }}
                                                    </span>
                                                @endif

                                                <span class="inline-flex items-center gap-1">
                                                    ❤️ {{ $post->likes_count ?? 0 }}
                                                </span>
                                                <span class="inline-flex items-center gap-1">
                                                    💬 {{ $post->comments_count ?? 0 }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-neutral-300">
                            Tu n’as encore posté aucun contenu.
                        </p>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
