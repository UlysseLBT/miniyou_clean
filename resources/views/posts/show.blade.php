@php
    use Illuminate\Support\Str;

    $user    = $post->user;
    $initial = $user ? Str::upper(Str::substr($user->name, 0, 1)) : 'U';
    $host    = $post->url ? parse_url($post->url, PHP_URL_HOST) : null;

    $likesCount     = $post->likes?->count() ?? 0;
    $commentsCount  = $post->comments?->count() ?? 0;
    $userHasLiked   = auth()->check()
        ? ($post->likes?->contains('user_id', auth()->id()) ?? false)
        : false;

    $page = request('page', 1);
@endphp

<x-app-layout>
    <div class="py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Titre + bouton retour --}}
            <div class="mb-5 flex items-center justify-between gap-3">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-white/5 border border-white/10 px-3 py-1 text-xs text-neutral-300">
                        <span class="h-2 w-2 rounded-full bg-red-500/80"></span>
                        <span>MiniYou</span>
                        <span class="opacity-60">·</span>
                        <span>Détails</span>
                    </div>
                    <h2 class="mt-2 font-semibold text-2xl text-white leading-tight">
                        Détails du post
                    </h2>
                </div>

                <button type="button"
                    onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href='{{ route('posts.index', ['page' => $page]) }}'; }"
                    class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium
                           bg-white/5 border border-red-500/40 text-white
                           hover:bg-white/10 transition shadow-[0_0_0_1px_rgba(239,68,68,.18)]">
                    ← Retour aux posts
                </button>
            </div>

            {{-- Flash messages --}}
            @if (session('status'))
                <div class="mb-4 rounded-2xl border border-white/10 bg-neutral-950/35 backdrop-blur px-4 py-3 text-sm text-neutral-200">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-2xl border border-red-500/20 bg-red-500/10 backdrop-blur px-4 py-3 text-sm text-red-300">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Carte du post --}}
            <article class="bg-neutral-950/35 border border-white/10 backdrop-blur rounded-2xl
                            shadow-[0_10px_35px_rgba(0,0,0,.35)] p-5 sm:p-6">
                <div class="flex items-start gap-4">

                    <div class="hidden sm:flex h-11 w-11 flex-none items-center justify-center rounded-full
                                border border-white/10 bg-white/5 text-neutral-200 font-semibold text-base">
                        {{ $initial }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-baseline justify-between gap-2">
                            <h1 class="text-lg sm:text-xl font-semibold text-white">
                                {{ $post->titre }}
                            </h1>
                            <div class="text-xs text-neutral-400 text-right">
                                @if($user)
                                    <span class="font-medium text-neutral-200">{{ $user->name }}</span>
                                    <span class="mx-1 opacity-60">·</span>
                                @endif
                                {{ $post->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>

                        @if($post->texte)
                            <p class="mt-3 text-sm sm:text-base leading-relaxed text-neutral-300">
                                {!! nl2br(e($post->texte)) !!}
                            </p>
                        @endif

                        @if($post->url)
                            <div class="mt-4 flex flex-wrap items-center gap-2">
                                <a href="{{ $post->url }}" target="_blank" rel="noopener noreferrer"
                                   class="text-sm sm:text-base text-red-300 hover:text-red-200 hover:underline break-all">
                                    {{ $post->url }}
                                </a>
                                @if($host)
                                    <span class="inline-flex items-center rounded-full bg-white/5 border border-white/10 px-2.5 py-0.5
                                                text-[11px] font-medium text-neutral-300">
                                        {{ $host }}
                                    </span>
                                @endif
                            </div>
                        @endif

                        @if($post->hashtags->isNotEmpty())
                            <div class="mt-3 flex flex-wrap gap-1.5">
                                @foreach ($post->hashtags as $tag)
                                    <a href="{{ route('hashtag.show', $tag->name) }}"
                                       class="inline-flex items-center rounded-full bg-white/5 border border-white/10
                                              px-2.5 py-0.5 text-[11px] font-medium text-red-300
                                              hover:bg-white/10 hover:text-red-200 transition">
                                        #{{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </article>

            {{-- Zone Like + compteurs + signaler post --}}
            <div class="mt-4 flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-4">
                    @auth
                        <form method="POST" action="{{ route('posts.like', $post) }}">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium transition
                                {{ $userHasLiked
                                    ? 'bg-red-500/20 border border-red-500/40 text-white hover:bg-red-500/25 shadow-[0_0_0_1px_rgba(239,68,68,.18)]'
                                    : 'bg-white/5 border border-white/10 text-neutral-200 hover:bg-white/10 hover:text-white'
                                }}">
                                @if ($userHasLiked)
                                    ❤️ <span class="ml-2">Je n'aime plus</span>
                                @else
                                    🤍 <span class="ml-2">J'aime</span>
                                @endif
                                <span class="ml-3 text-xs opacity-80">
                                    {{ $likesCount }} like{{ $likesCount > 1 ? 's' : '' }}
                                </span>
                            </button>
                        </form>
                    @else
                        <p class="text-xs text-neutral-400">
                            <a href="{{ route('login') }}" class="text-red-300 hover:text-red-200 hover:underline">Connecte-toi</a>
                            pour liker ce post.
                        </p>
                    @endauth
                </div>

                <div class="flex items-center gap-4">
                    <p class="text-xs text-neutral-400">
                        {{ $commentsCount }} commentaire{{ $commentsCount > 1 ? 's' : '' }}
                    </p>

                    @auth
                        @if($post->user_id !== auth()->id())
                            <button
                                onclick="document.getElementById('report-modal').classList.remove('hidden')"
                                class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-medium
                                       bg-white/5 border border-white/10 text-neutral-400
                                       hover:border-red-500/30 hover:text-red-400 transition">
                                🚩 Signaler
                            </button>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- Commentaires --}}
            <div class="mt-8">
                <h2 class="text-lg font-semibold text-white mb-3">
                    Commentaires <span class="text-neutral-400 font-normal">({{ $commentsCount }})</span>
                </h2>

                @auth
                    <form method="POST" action="{{ route('posts.comments.store', $post) }}"
                          class="mb-6 bg-neutral-950/35 border border-white/10 backdrop-blur rounded-2xl p-4 shadow-[0_10px_35px_rgba(0,0,0,.25)]">
                        @csrf
                        <textarea name="body" rows="3"
                                  class="w-full text-sm rounded-xl bg-white/5 border border-white/10 text-neutral-100
                                         placeholder:text-neutral-500 focus:border-red-500/50 focus:ring-red-500/20"
                                  placeholder="Écrire un commentaire..." required>{{ old('body') }}</textarea>

                        @error('body')
                            <p class="text-xs text-red-300 mt-2">{{ $message }}</p>
                        @enderror

                        <div class="mt-3 flex justify-end">
                            <button type="submit"
                                    class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium
                                           bg-white/5 border border-red-500/40 text-white
                                           hover:bg-white/10 transition shadow-[0_0_0_1px_rgba(239,68,68,.18)]">
                                Publier
                            </button>
                        </div>
                    </form>
                @else
                    <p class="text-sm text-neutral-400 mb-4">
                        <a href="{{ route('login') }}" class="text-red-300 hover:text-red-200 hover:underline">Connecte-toi</a>
                        pour laisser un commentaire.
                    </p>
                @endauth

                <div class="space-y-4">
                    @forelse ($post->comments as $comment)
                        <div class="bg-neutral-950/35 border border-white/10 backdrop-blur rounded-2xl p-4
                                    shadow-[0_10px_35px_rgba(0,0,0,.25)]">

                            <div class="flex justify-between text-xs text-neutral-400 mb-1">
                                <span class="font-medium text-neutral-200">
                                    {{ $comment->user->name ?? 'Utilisateur' }}
                                </span>
                                <span>{{ $comment->created_at->diffForHumans() }}</span>
                            </div>

                            <p class="text-sm text-neutral-300 whitespace-pre-wrap">
                                {{ $comment->body }}
                            </p>

                            <div class="mt-2 flex items-center justify-between">

                                {{-- Supprimer (auteur uniquement) --}}
                                @if (auth()->id() === $comment->user_id)
                                    <form method="POST" action="{{ route('comments.destroy', $comment) }}"
                                          onsubmit="return confirm('Supprimer ce commentaire ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-xs text-neutral-400 hover:text-red-300 transition">
                                            Supprimer
                                        </button>
                                    </form>
                                @else
                                    <div></div>
                                @endif

                                {{-- Signaler (autres utilisateurs) --}}
                                @auth
                                    @if(auth()->id() !== $comment->user_id)
                                        <button
                                            onclick="document.getElementById('report-comment-{{ $comment->id }}').classList.remove('hidden')"
                                            class="text-xs text-neutral-500 hover:text-red-400 transition">
                                            🚩 Signaler
                                        </button>
                                    @endif
                                @endauth
                            </div>
                        </div>

                        {{-- Modal signalement commentaire --}}
                        @auth
                            @if(auth()->id() !== $comment->user_id)
                                <div id="report-comment-{{ $comment->id }}"
                                     class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
                                     onclick="if(event.target===this) this.classList.add('hidden')">

                                    <div class="w-full max-w-md mx-4 bg-neutral-900 border border-white/10 rounded-2xl shadow-2xl p-6">

                                        <h3 class="text-lg font-semibold text-white mb-1">Signaler ce commentaire</h3>
                                        <p class="text-sm text-neutral-400 mb-1">
                                            De <span class="text-neutral-300">{{ $comment->user->name ?? 'Utilisateur' }}</span> :
                                        </p>
                                        <p class="text-sm text-neutral-500 italic mb-4 line-clamp-2">
                                            "{{ Str::limit($comment->body, 80) }}"
                                        </p>

                                        <form method="POST" action="{{ route('comments.report', $comment) }}">
                                            @csrf

                                            <div class="space-y-2 mb-5">
                                                @foreach(\App\Models\Report::REASONS as $value => $label)
                                                    <label class="flex items-center gap-3 p-3 rounded-xl border border-white/10
                                                                  bg-white/5 hover:bg-white/10 cursor-pointer transition">
                                                        <input type="radio" name="reason" value="{{ $value }}"
                                                               class="accent-red-500" required>
                                                        <span class="text-sm text-neutral-200">{{ $label }}</span>
                                                    </label>
                                                @endforeach
                                            </div>

                                            <div class="flex gap-3">
                                                <button type="submit"
                                                        class="flex-1 rounded-full px-4 py-2 text-sm font-medium
                                                               bg-white/5 border border-red-500/40 text-white
                                                               hover:bg-white/10 transition">
                                                    Envoyer
                                                </button>
                                                <button type="button"
                                                        onclick="document.getElementById('report-comment-{{ $comment->id }}').classList.add('hidden')"
                                                        class="flex-1 rounded-full px-4 py-2 text-sm font-medium
                                                               bg-white/5 border border-white/10 text-neutral-300
                                                               hover:bg-white/10 transition">
                                                    Annuler
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endauth

                    @empty
                        <p class="text-sm text-neutral-400">
                            Aucun commentaire pour l'instant.
                        </p>
                    @endforelse
                </div>
            </div>

            {{-- Suppression du post avec confirmation --}}
            @can('delete', $post)
                <div class="mt-6 flex justify-end">
                    <form method="POST" action="{{ route('posts.destroy', $post) }}"
                          onsubmit="return confirm('Supprimer ce post définitivement ? Cette action est irréversible.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold text-white
                                       bg-red-600/80 hover:bg-red-600 transition border border-red-500/40
                                       shadow-[0_0_0_1px_rgba(239,68,68,.18)]">
                            Supprimer ce post
                        </button>
                    </form>
                </div>
            @endcan

        </div>
    </div>

    {{-- Modal signalement post --}}
    @auth
        @if($post->user_id !== auth()->id())
            <div id="report-modal"
                 class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
                 onclick="if(event.target===this) this.classList.add('hidden')">

                <div class="w-full max-w-md mx-4 bg-neutral-900 border border-white/10 rounded-2xl shadow-2xl p-6">

                    <h3 class="text-lg font-semibold text-white mb-1">Signaler ce post</h3>
                    <p class="text-sm text-neutral-400 mb-4">Choisis la raison du signalement.</p>

                    <form method="POST" action="{{ route('posts.report', $post) }}">
                        @csrf

                        <div class="space-y-2 mb-5">
                            @foreach(\App\Models\Report::REASONS as $value => $label)
                                <label class="flex items-center gap-3 p-3 rounded-xl border border-white/10
                                              bg-white/5 hover:bg-white/10 cursor-pointer transition">
                                    <input type="radio" name="reason" value="{{ $value }}"
                                           class="accent-red-500" required>
                                    <span class="text-sm text-neutral-200">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>

                        <div class="flex gap-3">
                            <button type="submit"
                                    class="flex-1 rounded-full px-4 py-2 text-sm font-medium
                                           bg-white/5 border border-red-500/40 text-white
                                           hover:bg-white/10 transition">
                                Envoyer
                            </button>
                            <button type="button"
                                    onclick="document.getElementById('report-modal').classList.add('hidden')"
                                    class="flex-1 rounded-full px-4 py-2 text-sm font-medium
                                           bg-white/5 border border-white/10 text-neutral-300
                                           hover:bg-white/10 transition">
                                Annuler
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endauth

</x-app-layout>