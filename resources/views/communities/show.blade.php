@php
    use Illuminate\Support\Str;

    $isOwner  = auth()->check() && auth()->id() === $community->owner_id;
    $isMember = auth()->check() && (
        $community->owner_id === auth()->id()
        || $community->members->contains('id', auth()->id())
    );

    $canPost  = $isOwner || $isMember;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
            <div class="min-w-0">
                <div class="inline-flex items-center gap-2 rounded-full bg-white/5 border border-white/10 px-3 py-1 text-xs text-neutral-300">
                    <span class="h-2 w-2 rounded-full bg-red-500/80"></span>
                    <span>MiniYou</span>
                    <span class="opacity-60">·</span>
                    <span>Communauté</span>
                </div>

                <h2 class="mt-2 font-semibold text-2xl text-white leading-tight truncate">
                    {{ $community->name }}
                </h2>

                <div class="mt-1 flex flex-wrap items-center gap-2 text-sm text-neutral-400">
                    <span>
                        Créée par
                        <span class="text-neutral-200 font-medium">
                            {{ $community->owner->display_name ?? $community->owner->name }}
                        </span>
                    </span>

                    <span class="opacity-60">·</span>

                    @if($community->visibility === 'private')
                        <span class="inline-flex items-center rounded-full bg-white/5 border border-white/10 px-2 py-0.5 text-[11px] font-medium text-neutral-200">
                            Privée
                        </span>
                    @else
                        <span class="inline-flex items-center rounded-full bg-white/5 border border-white/10 px-2 py-0.5 text-[11px] font-medium text-neutral-300">
                            Publique
                        </span>
                    @endif
                </div>

                @if($community->description)
                    <p class="mt-3 text-sm text-neutral-300 max-w-3xl">
                        {{ $community->description }}
                    </p>
                @endif
            </div>

            {{-- Actions à droite --}}
            @auth
                <div class="flex flex-wrap items-center gap-2 shrink-0">
                    @if ($canPost)
                        <a href="{{ route('communities.posts.create', $community) }}"
                           class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium
                                  bg-white/5 border border-red-500/40 text-white
                                  hover:bg-white/10 transition
                                  shadow-[0_0_0_1px_rgba(239,68,68,.18)]">
                            + Nouveau post
                        </a>
                    @endif

                    @if ($isOwner)
                        <form method="POST"
                              action="{{ route('communities.destroy', $community) }}"
                              onsubmit="return confirm('Supprimer définitivement cette communauté et tous ses posts ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium
                                           bg-red-500/15 border border-red-500/35 text-red-100
                                           hover:bg-red-500/25 transition">
                                Supprimer
                            </button>
                        </form>
                    @else
                        @if ($isMember)
                            <form method="POST" action="{{ route('communities.leave', $community) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium
                                               bg-white/5 border border-white/10 text-neutral-200
                                               hover:bg-white/10 transition">
                                    Quitter
                                </button>
                            </form>
                        @else
                            @if($community->visibility === 'public')
                                <form method="POST" action="{{ route('communities.join', $community) }}">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium
                                                   bg-white/5 border border-red-500/40 text-white
                                                   hover:bg-white/10 transition
                                                   shadow-[0_0_0_1px_rgba(239,68,68,.18)]">
                                        Rejoindre
                                    </button>
                                </form>
                            @endif
                        @endif
                    @endif
                </div>
            @endauth
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- DEMANDES EN ATTENTE (owner uniquement) --}}
            @if($isOwner)
                <div class="bg-neutral-950/35 border border-white/10 backdrop-blur rounded-2xl
                            shadow-[0_10px_35px_rgba(0,0,0,.30)]">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-white mb-4">
                            Demandes pour rejoindre ({{ $pendingRequests->count() }})
                        </h3>

                        @if($pendingRequests->isEmpty())
                            <p class="text-neutral-300">Aucune demande en attente.</p>
                        @else
                            <div class="space-y-3">
                                @foreach($pendingRequests as $req)
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3
                                                rounded-xl border border-white/10 bg-white/5 p-4">
                                        <div class="min-w-0">
                                            <div class="font-medium text-white">
                                                {{ $req->user->display_name ?? $req->user->name ?? $req->user->username ?? 'Utilisateur' }}
                                            </div>
                                            <div class="text-xs text-neutral-400">
                                                Demandé {{ optional($req->created_at)->diffForHumans() }}
                                            </div>
                                            @if($req->message)
                                                <div class="text-sm text-neutral-300 mt-2">
                                                    {{ $req->message }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex items-center gap-2 shrink-0">
                                            <form method="POST" action="{{ route('communities.joinRequests.approve', [$community, $req]) }}">
                                                @csrf
                                                <button class="px-3 py-2 rounded-full bg-white/5 border border-emerald-500/40 text-emerald-100 text-sm hover:bg-white/10 transition">
                                                    Accepter
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('communities.joinRequests.deny', [$community, $req]) }}">
                                                @csrf
                                                <button class="px-3 py-2 rounded-full bg-white/5 border border-white/10 text-neutral-200 text-sm hover:bg-white/10 transition">
                                                    Refuser
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- POSTS DE LA COMMUNAUTÉ --}}
            <div class="bg-neutral-950/35 border border-white/10 backdrop-blur rounded-2xl
                        shadow-[0_10px_35px_rgba(0,0,0,.30)]">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">
                        Posts de la communauté
                    </h3>

                    @if($posts->count())
                        <div class="space-y-4">
                            @foreach($posts as $post)
                                <article
                                    role="link"
                                    tabindex="0"
                                    onclick="window.location='{{ route('posts.show', $post) }}'"
                                    onkeydown="if(event.key==='Enter' || event.key===' '){ window.location='{{ route('posts.show', $post) }}' }"
                                    class="cursor-pointer rounded-2xl border border-white/10 bg-white/5 p-5
                                           hover:bg-white/10 hover:border-white/20 hover:-translate-y-0.5 transition
                                           focus:outline-none focus:ring-2 focus:ring-red-500/30">

                                    <div class="flex items-start justify-between gap-3 text-xs text-neutral-400">
                                        <span>
                                            Posté par
                                            <span class="text-neutral-200 font-medium">
                                                {{ $post->user->display_name ?? $post->user->name }}
                                            </span>
                                        </span>
                                        <span>{{ $post->created_at->diffForHumans() }}</span>
                                    </div>

                                    <div class="mt-2 font-semibold text-white text-sm sm:text-base">
                                        {{ $post->titre }}
                                    </div>

                                    @if($post->texte)
                                        <p class="mt-2 text-sm text-neutral-300">
                                            {{ Str::limit($post->texte, 200) }}
                                        </p>
                                    @endif
                                </article>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <p class="text-neutral-300">
                            Aucun post dans cette communauté pour l’instant.
                        </p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
