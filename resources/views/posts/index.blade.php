@php
    use Illuminate\Support\Str;
@endphp

<x-app-layout>
    {{-- Header avec titre --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Posts') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- Bouton + Nouveau post en haut à gauche --}}
            <div class="mb-4">
                <a href="{{ route('posts.create') }}"
                   class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium
                          bg-emerald-500 text-white hover:bg-emerald-600 shadow-sm transition">
                    + Nouveau post
                </a>
            </div>

            {{-- Message de statut --}}
            @if (session('status'))
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Liste des posts --}}
            <div class="space-y-4">
                @forelse ($posts as $post)
                    @php
                        $user          = $post->user;
                        $initial       = $user ? Str::upper(Str::substr($user->name, 0, 1)) : 'U';
                        $host          = $post->url ? parse_url($post->url, PHP_URL_HOST) : null;
                        $likesCount    = $post->likes_count ?? 0;
                        $commentsCount = $post->comments_count ?? 0;
                    @endphp

                    {{-- Toute la carte est cliquable = détails du post --}}
                <article
                    onclick="window.location='{{ route('posts.show', [
                        'post' => $post->id,
                        'page' => $posts->currentPage(),
                    ]) }}'"
                    class="cursor-pointer bg-white border border-slate-100 rounded-xl shadow-sm p-4 sm:p-5
                        hover:shadow-md hover:-translate-y-0.5 transition">

                        <div class="flex items-start gap-4">

                            {{-- Avatar initiale --}}
                            <div class="hidden sm:flex h-10 w-10 flex-none items-center justify-center
                                        rounded-full bg-emerald-100 text-emerald-700 font-semibold">
                                {{ $initial }}
                            </div>

                            <div class="flex-1 min-w-0">
                                {{-- Titre + auteur + date --}}
                                <div class="flex flex-wrap items-baseline justify-between gap-2">
                                    <h3 class="text-base sm:text-lg font-semibold text-slate-900">
                                        {{ $post->titre }}
                                    </h3>

                                    <div class="text-xs text-slate-400 text-right">
                                        @if ($user)
                                            <span class="font-medium text-slate-500">{{ $user->name }}</span>
                                            <span class="mx-1">·</span>
                                        @endif
                                        {{ $post->created_at->diffForHumans() }}
                                    </div>
                                </div>

                                {{-- Texte --}}
                                @if ($post->texte)
                                    <p class="mt-1 text-sm text-slate-600">
                                        {{ Str::limit($post->texte, 180) }}
                                    </p>
                                @endif

                                {{-- Lien + domaine --}}
                                @if ($post->url)
                                    <div class="mt-2 flex flex-wrap items-center gap-2">
                                        {{-- Lien externe : on empêche l’ouverture de la page du post --}}
                                        <a href="{{ $post->url }}"
                                           target="_blank"
                                           onclick="event.stopPropagation();"
                                           class="text-sm text-emerald-600 hover:text-emerald-700 hover:underline break-all">
                                            {{ $post->url }}
                                        </a>

                                        @if ($host)
                                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5
                                                         text-[11px] font-medium text-slate-600"
                                                  onclick="event.stopPropagation();">
                                                {{ $host }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                {{-- Ligne likes + commentaires --}}
                                <div class="mt-3 flex items-center gap-4 text-xs text-slate-500">
                                    <span class="inline-flex items-center gap-1">
                                        ❤️
                                        <span>{{ $likesCount }}</span>
                                    </span>

                                    <span class="inline-flex items-center gap-1">
                                        💬
                                        <span>{{ $commentsCount }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="bg-white rounded-xl shadow-sm p-6 text-center text-slate-500">
                        Aucun post pour le moment.<br>
                        <a href="{{ route('posts.create') }}" class="text-emerald-600 hover:underline">
                            Crée ton premier post
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
