@php
    use Illuminate\Support\Str;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Posts') }}
            </h2>

            <a href="{{ route('posts.create') }}"
               class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium
                      bg-emerald-500 text-white hover:bg-emerald-600 shadow-sm transition">
                + Nouveau post
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- Message de statut --}}
            @if(session('status'))
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Liste des posts --}}
            <div class="space-y-4">
                @forelse($posts as $post)
                    @php
                        $user = $post->user;
                        $initial = $user ? Str::upper(Str::substr($user->name, 0, 1)) : 'U';
                        $host = $post->url ? parse_url($post->url, PHP_URL_HOST) : null;
                    @endphp

                    <article class="bg-white border border-slate-100 rounded-xl shadow-sm p-4 sm:p-5
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
                                        @if($user)
                                            <span class="font-medium text-slate-500">{{ $user->name }}</span>
                                            <span class="mx-1">·</span>
                                        @endif
                                        {{ $post->created_at->diffForHumans() }}
                                    </div>
                                </div>

                                {{-- Texte --}}
                                @if($post->texte)
                                    <p class="mt-1 text-sm text-slate-600">
                                        {{ Str::limit($post->texte, 180) }}
                                    </p>
                                @endif

                                {{-- Lien + domaine --}}
                                @if($post->url)
                                    <div class="mt-2 flex flex-wrap items-center gap-2">
                                        <a href="{{ $post->url }}" target="_blank"
                                           class="text-sm text-emerald-600 hover:text-emerald-700 hover:underline break-all">
                                            {{ $post->url }}
                                        </a>


                                        @if($host)
                                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5
                                                         text-[11px] font-medium text-slate-600">
                                                {{ $host }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                <a href="{{ route('posts.show', $post) }}" class="post-url" style="margin-top:.3rem;display:inline-block;">
                                    Voir le post
                                </a>
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

            {{-- Pagination + bouton en bas --}}
            <div class="mt-6 flex items-center justify-between">
                <div>
                    {{ $posts->links() }}
                </div>

                <a href="{{ route('posts.create') }}"
                   class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium
                          bg-emerald-500 text-black hover:bg-emerald-600 shadow-sm transition">
                    + Nouveau post
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
