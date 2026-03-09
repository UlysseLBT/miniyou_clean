@php use Illuminate\Support\Str; @endphp

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

                {{-- En-tête + barre de recherche --}}
                <div class="rounded-3xl border border-white/10 bg-neutral-950/35 backdrop-blur
                            shadow-[0_10px_35px_rgba(0,0,0,.35)] px-6 py-6 sm:px-8 sm:py-7">

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-white">Membres</h1>
                            <p class="text-sm text-neutral-400 mt-0.5">
                                {{ $users->total() }} membre{{ $users->total() > 1 ? 's' : '' }}
                                @if($query)
                                    pour <span class="text-neutral-200">"{{ $query }}"</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Barre de recherche --}}
                    <form method="GET" action="{{ route('users.index') }}" role="search">
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                <svg class="h-4 w-4 text-neutral-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                </svg>
                            </div>
                            <input
                                type="search"
                                name="q"
                                value="{{ $query }}"
                                placeholder="Rechercher par nom ou username…"
                                autofocus
                                class="w-full rounded-xl border border-white/10 bg-white/5 py-3 pl-10 pr-4
                                       text-sm text-neutral-100 placeholder-neutral-500
                                       focus:outline-none focus:ring-1 focus:ring-red-500/50 focus:border-red-500/40
                                       transition"
                            />
                            @if($query)
                                <a href="{{ route('users.index') }}"
                                   class="absolute inset-y-0 right-3 flex items-center text-neutral-400 hover:text-neutral-200 transition text-xs">
                                    Effacer
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Résultats --}}
                @if($users->isEmpty())
                    <div class="rounded-3xl border border-white/10 bg-neutral-950/35 backdrop-blur
                                shadow-[0_10px_35px_rgba(0,0,0,.35)] p-12 text-center">
                        <div class="text-4xl mb-3">🔍</div>
                        <p class="text-neutral-300 font-medium">Aucun membre trouvé</p>
                        @if($query)
                            <p class="text-sm text-neutral-500 mt-1">
                                Essaie un autre terme de recherche.
                            </p>
                        @endif
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($users as $user)
                            @php
                                $displayName = $user->display_name ?? $user->name;
                                $initial     = Str::upper(Str::substr($displayName, 0, 1));
                                $avatarUrl   = null;
                                if (!empty($user->avatar_path)) {
                                    $avatarUrl = asset('storage/'.$user->avatar_path);
                                }
                            @endphp

                            <a href="{{ route('users.show', $user->id) }}"
                               class="group flex items-center gap-4 rounded-2xl border border-white/10 bg-neutral-950/35 backdrop-blur
                                      p-4 hover:bg-white/5 hover:border-white/20 hover:-translate-y-0.5
                                      transition shadow-[0_4px_20px_rgba(0,0,0,.25)]">

                                {{-- Avatar --}}
                                @if($avatarUrl)
                                    <img src="{{ $avatarUrl }}"
                                         alt="{{ $displayName }}"
                                         class="h-12 w-12 rounded-full object-cover border border-white/15 shrink-0">
                                @else
                                    <div class="h-12 w-12 rounded-full bg-white/5 border border-white/10
                                                flex items-center justify-center text-base font-semibold
                                                text-neutral-200 shrink-0">
                                        {{ $initial }}
                                    </div>
                                @endif

                                {{-- Infos --}}
                                <div class="min-w-0 flex-1">
                                    <p class="font-semibold text-white text-sm truncate group-hover:text-red-200 transition">
                                        {{ $displayName }}
                                    </p>
                                    @if($user->username)
                                        <p class="text-xs text-neutral-400 truncate">
                                            @{{ $user->username }}
                                        </p>
                                    @endif
                                    @if($user->bio)
                                        <p class="text-xs text-neutral-500 truncate mt-0.5">
                                            {{ Str::limit($user->bio, 60) }}
                                        </p>
                                    @endif
                                </div>

                                {{-- Flèche --}}
                                <svg class="h-4 w-4 text-neutral-600 group-hover:text-neutral-300 transition shrink-0"
                                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if($users->hasPages())
                        <div class="flex justify-center">
                            {{ $users->links() }}
                        </div>
                    @endif
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
