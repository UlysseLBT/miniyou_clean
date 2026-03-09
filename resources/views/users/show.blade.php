@php
    use Illuminate\Support\Str;

    $displayName = $user->display_name ?? $user->name;
    $initial     = Str::upper(Str::substr($displayName, 0, 1));

    $avatarUrl = null;
    if (!empty($user->avatar_path)) {
        $avatarUrl = asset('storage/'.$user->avatar_path);
    }

    $isSelf = auth()->check() && auth()->id() === $user->id;
@endphp

<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen relative overflow-hidden bg-[#050506] text-neutral-100">
        {{-- Glows décoratifs --}}
        <div aria-hidden="true" class="pointer-events-none absolute inset-0">
            <div class="absolute -top-44 -left-44 h-[520px] w-[520px] rounded-full bg-red-900/35 blur-[130px]"></div>
            <div class="absolute -top-44 -right-44 h-[520px] w-[520px] rounded-full bg-amber-900/18 blur-[130px]"></div>
            <div class="absolute -bottom-64 left-1/3 h-[640px] w-[640px] rounded-full bg-indigo-900/22 blur-[150px]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,rgba(255,255,255,0.08),transparent_55%)]"></div>
        </div>

        <div class="relative py-10">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

                {{-- Retour --}}
                <a href="{{ route('users.index') }}"
                   class="inline-flex items-center gap-1.5 text-sm text-neutral-400 hover:text-neutral-200 transition">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    Membres
                </a>

                {{-- Bandeau profil --}}
                <div class="rounded-3xl border border-white/10 bg-neutral-950/35 backdrop-blur
                            shadow-[0_10px_35px_rgba(0,0,0,.35)] px-6 py-6 sm:px-8 sm:py-7">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">

                        {{-- Gauche : avatar + infos --}}
                        <div class="flex flex-col gap-4">
                            <div class="flex items-center gap-4">
                                @if($avatarUrl)
                                    <img src="{{ $avatarUrl }}"
                                         alt="{{ $displayName }}"
                                         class="h-16 w-16 rounded-full object-cover border border-white/15">
                                @else
                                    <div class="h-16 w-16 rounded-full bg-white/5 border border-white/10
                                                flex items-center justify-center text-xl font-semibold">
                                        {{ $initial }}
                                    </div>
                                @endif

                                <div>
                                    <h1 class="text-xl font-semibold text-white">{{ $displayName }}</h1>
                                    @if($user->username)
                                        <p class="text-sm text-neutral-400">@{{ $user->username }}</p>
                                    @endif
                                </div>
                            </div>

                            @if(!empty($user->bio))
                                <p class="text-sm text-neutral-200/90 max-w-xl">{{ $user->bio }}</p>
                            @endif

                            {{-- Liens sociaux --}}
                            <div class="flex flex-wrap items-center gap-2 text-xs">
                                @if(!empty($user->website))
                                    <a href="{{ $user->website }}" target="_blank"
                                       class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-white/5 border border-white/10 text-neutral-200 hover:bg-white/10 transition">
                                        🌐 <span class="ml-1 truncate max-w-[180px]">{{ $user->website }}</span>
                                    </a>
                                @endif
                                @if(!empty($user->twitter))
                                    <a href="{{ Str::startsWith($user->twitter, ['http://','https://']) ? $user->twitter : 'https://twitter.com/'.ltrim($user->twitter, '@') }}"
                                       target="_blank"
                                       class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-white/5 border border-white/10 text-neutral-200 hover:bg-white/10 transition">
                                        𝕏 <span class="ml-1">{{ ltrim($user->twitter, '@') }}</span>
                                    </a>
                                @endif
                                @if(!empty($user->instagram))
                                    <a href="{{ Str::startsWith($user->instagram, ['http://','https://']) ? $user->instagram : 'https://instagram.com/'.ltrim($user->instagram, '@') }}"
                                       target="_blank"
                                       class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-white/5 border border-white/10 text-neutral-200 hover:bg-white/10 transition">
                                        📸 <span class="ml-1">{{ ltrim($user->instagram, '@') }}</span>
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Droite : bouton follow + stats --}}
                        <div class="flex flex-col items-start sm:items-end gap-4">

                            {{-- Bouton follow / éditer --}}
                            @auth
                                @if($isSelf)
                                    <a href="{{ route('profile.edit') }}"
                                       class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium
                                              bg-white/5 border border-red-500/40 text-white
                                              hover:bg-white/10 transition shadow-[0_0_0_1px_rgba(239,68,68,.20)]">
                                        Modifier mon profil
                                    </a>
                                @else
                                    <form method="POST" action="{{ route('users.follow', $user->id) }}">
                                        @csrf
                                        @if($isFollowing)
                                            <button type="submit"
                                                    class="inline-flex items-center gap-2 rounded-full px-5 py-2 text-sm font-medium
                                                           bg-white/5 border border-white/20 text-neutral-300
                                                           hover:bg-red-950/40 hover:border-red-500/40 hover:text-red-300
                                                           transition">
                                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                                </svg>
                                                Suivi
                                            </button>
                                        @else
                                            <button type="submit"
                                                    class="inline-flex items-center gap-2 rounded-full px-5 py-2 text-sm font-medium
                                                           bg-red-600/80 border border-red-500/60 text-white
                                                           hover:bg-red-600 transition
                                                           shadow-[0_0_12px_rgba(239,68,68,.25)]">
                                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                                </svg>
                                                Suivre
                                            </button>
                                        @endif
                                    </form>
                                @endif
                            @endauth

                            {{-- Stats --}}
                            <div class="flex gap-6 text-sm">
                                <div class="text-center">
                                    <div class="text-lg font-semibold text-white">{{ $user->posts_count }}</div>
                                    <div class="text-xs uppercase tracking-wide text-neutral-400">Post{{ $user->posts_count > 1 ? 's' : '' }}</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-semibold text-white">{{ $user->followers_count }}</div>
                                    <div class="text-xs uppercase tracking-wide text-neutral-400">Abonnés</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-semibold text-white">{{ $user->following_count }}</div>
                                    <div class="text-xs uppercase tracking-wide text-neutral-400">Abonnements</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Posts de l'utilisateur --}}
                <div class="rounded-3xl border border-white/10 bg-neutral-950/35 backdrop-blur
                            shadow-[0_10px_35px_rgba(0,0,0,.35)] p-6 sm:p-7">
                    <h3 class="text-lg font-semibold text-white mb-4">Posts</h3>

                    @if($posts->count())
                        <div class="space-y-4">
                            @foreach($posts as $post)
                                <article
                                    onclick="window.location='{{ route('posts.show', $post) }}'"
                                    class="cursor-pointer rounded-2xl border border-white/10 bg-white/5
                                           p-4 hover:bg-white/10 hover:border-white/20 hover:-translate-y-0.5 transition">

                                    <div class="flex items-start gap-3">
                                        @if($avatarUrl)
                                            <img src="{{ $avatarUrl }}" alt="Avatar"
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
                                                <span class="text-xs text-neutral-400 shrink-0">
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
                                                    <span class="truncate max-w-xs text-red-300">🔗 {{ $post->url }}</span>
                                                @endif
                                                <span class="inline-flex items-center gap-1">❤️ {{ $post->likes_count ?? 0 }}</span>
                                                <span class="inline-flex items-center gap-1">💬 {{ $post->comments_count ?? 0 }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-neutral-400">Aucun post pour l'instant.</p>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
