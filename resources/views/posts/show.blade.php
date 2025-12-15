@php
    use Illuminate\Support\Str;

    $user    = $post->user;
    $initial = $user ? Str::upper(Str::substr($user->name, 0, 1)) : 'U';
    $host    = $post->url ? parse_url($post->url, PHP_URL_HOST) : null;

    $likesCount     = $post->likes->count() ?? 0;
    $commentsCount  = $post->comments->count() ?? 0;
    $userHasLiked   = auth()->check()
        ? $post->likes->contains('user_id', auth()->id())
        : false;

    // page actuelle (ex: /posts/12?page=3)
    $page = request('page', 1);
@endphp

<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Titre + bouton retour --}}
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Détails du post
                </h2>

                <button
                    type="button"
                    onclick="
                        if (window.history.length > 1) {
                            window.history.back();
                        } else {
                            window.location.href='{{ route('posts.index', ['page' => $page]) }}';
                        }
                    "
                    class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium
                            bg-red-200 text-gray-700 hover:bg-red-300">
                     Retour aux posts
                </button>
            </div>

            {{-- Carte du post --}}
            <article class="bg-white shadow-sm rounded-xl p-6 sm:p-7 border border-slate-100">
                <div class="flex items-start gap-4">

                    {{-- Avatar initiale --}}
                    <div class="hidden sm:flex h-11 w-11 flex-none items-center justify-center
                                rounded-full bg-emerald-100 text-emerald-700 font-semibold text-base">
                        {{ $initial }}
                    </div>

                    <div class="flex-1 min-w-0">
                        {{-- Titre + meta --}}
                        <div class="flex flex-wrap items-baseline justify-between gap-2">
                            <h1 class="text-lg sm:text-xl font-semibold text-slate-900">
                                {{ $post->titre }}
                            </h1>

                            <div class="text-xs text-slate-400 text-right">
                                @if($user)
                                    <span class="font-medium text-slate-500">{{ $user->name }}</span>
                                    <span class="mx-1">·</span>
                                @endif
                                {{ $post->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>

                        {{-- Texte complet --}}
                        @if($post->texte)
                            <p class="mt-3 text-sm sm:text-base leading-relaxed text-slate-700">
                                {!! nl2br(e($post->texte)) !!}
                            </p>
                        @endif

                        {{-- Lien --}}
                        @if($post->url)
                            <div class="mt-4 flex flex-wrap items-center gap-2">
                                <a href="{{ $post->url }}" target="_blank"
                                   class="text-sm sm:text-base text-emerald-600 hover:text-emerald-700 hover:underline break-all">
                                    {{ $post->url }}
                                </a>

                                @if($host)
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5
                                                text-[11px] font-medium text-slate-600">
                                        {{ $host }}
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </article>

            {{-- Zone Like + compteurs --}}
            <div class="mt-4 flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-4">
                    @auth
                        <form method="POST" action="{{ route('posts.like', $post) }}">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium
                                        {{ $userHasLiked ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-700' }}
                                        hover:bg-emerald-600 hover:text-white transition">
                                @if ($userHasLiked)
                                    ❤️
                                    <span class="ml-2">Je n'aime plus</span>
                                @else
                                    🤍
                                    <span class="ml-2">J'aime</span>
                                @endif

                                <span class="ml-3 text-xs opacity-80">
                                    {{ $likesCount }} like{{ $likesCount > 1 ? 's' : '' }}
                                </span>
                            </button>
                        </form>
                    @else
                        <p class="text-xs text-slate-500">
                            <a href="{{ route('login') }}" class="text-emerald-600 hover:underline">Connecte-toi</a>
                            pour liker ce post.
                        </p>
                    @endauth
                </div>

                <p class="text-xs text-slate-500">
                    {{ $commentsCount }} commentaire{{ $commentsCount > 1 ? 's' : '' }}
                </p>
            </div>

            {{-- Commentaires --}}
            <div class="mt-8">
                <h2 class="text-lg font-semibold text-slate-900 mb-3">
                    Commentaires ({{ $commentsCount }})
                </h2>

                {{-- Formulaire de commentaire --}}
                @auth
                    <form method="POST" action="{{ route('posts.comments.store', $post) }}"
                          class="mb-6 bg-white border border-slate-100 rounded-xl p-4 shadow-sm">
                        @csrf

                        <textarea name="body" rows="3"
                                  class="w-full text-sm rounded-lg border-slate-200 focus:border-emerald-500 focus:ring-emerald-500"
                                  placeholder="Écrire un commentaire..." required>{{ old('body') }}</textarea>

                        @error('body')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror

                        <div class="mt-3 flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium
                                        bg-emerald-500 text-white hover:bg-emerald-600">
                                Publier
                            </button>
                        </div>
                    </form>
                @else
                    <p class="text-sm text-slate-500 mb-4">
                        <a href="{{ route('login') }}" class="text-emerald-600 hover:underline">Connecte-toi</a>
                        pour laisser un commentaire.
                    </p>
                @endauth

                {{-- Liste des commentaires --}}
                <div class="space-y-4">
                    @forelse ($post->comments as $comment)
                        <div class="bg-white border border-slate-100 rounded-xl p-4 shadow-sm">
                            <div class="flex justify-between text-xs text-slate-400 mb-1">
                                <span class="font-medium text-slate-600">
                                    {{ $comment->user->name }}
                                </span>
                                <span>{{ $comment->created_at->diffForHumans() }}</span>
                            </div>

                            <p class="text-sm text-slate-700 whitespace-pre-wrap">
                                {{ $comment->body }}
                            </p>

                            @if (auth()->id() === $comment->user_id)
                                <form method="POST" action="{{ route('comments.destroy', $comment) }}"
                                      class="mt-2 text-right">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-xs text-slate-400 hover:text-red-500">
                                        Supprimer
                                    </button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">
                            Aucun commentaire pour l'instant.
                        </p>
                    @endforelse
                </div>
            </div>

            {{-- Actions en bas : suppression du post --}}
            @can('delete', $post)
                <div class="mt-6 flex justify-end">
                    <form method="POST" action="{{ route('posts.destroy', $post) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-500 border border-transparent rounded-full
                                    font-semibold text-xs text-white uppercase tracking-widest
                                    hover:bg-red-600 active:bg-red-700 focus:outline-none focus:ring-2
                                    focus:ring-red-500 focus:ring-offset-2">
                            Supprimer ce post
                        </button>
                    </form>
                </div>
            @endcan

        </div>
    </div>
</x-app-layout>
