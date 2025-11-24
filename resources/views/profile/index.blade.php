@php
    use Illuminate\Support\Str;

    $displayName = $user->display_name ?? $user->name;
    $initial     = Str::upper(Str::substr($displayName, 0, 1));

    $totalPosts    = $posts->count();
    $totalLikes    = $posts->sum('likes_count');
    $totalComments = $posts->sum('comments_count');
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Mon profil
            </h2>

            <a href="{{ route('profile.edit') }}"
               class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium
                      bg-emerald-500 text-white hover:bg-emerald-600 shadow-sm transition">
                Modifier mon profil
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Bandeau profil --}}
            <div class="bg-slate-900 text-white sm:rounded-xl shadow-sm px-6 py-6 sm:px-8 sm:py-7
                        flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <div class="flex flex-col gap-4">

                    {{-- Avatar + nom + email --}}
                    <div class="flex items-center gap-4">
                        {{-- AVATAR ICI --}}
                        @if(!empty($user->avatar_path))
                            <img src="{{ asset('storage/'.$user->avatar_path) }}"
                                 alt="Avatar"
                                 class="h-16 w-16 rounded-full object-cover border border-white/20">
                        @else
                            <div class="h-16 w-16 rounded-full bg-emerald-400 flex items-center justify-center text-xl font-semibold">
                                {{ $initial }}
                            </div>
                        @endif

                        <div>
                            <h1 class="text-xl font-semibold">
                                {{ $displayName }}
                            </h1>
                            <p class="text-sm text-slate-300">
                                {{ $user->email }}
                            </p>
                        </div>
                    </div>

                    {{-- Bio --}}
                    @if(!empty($user->bio))
                        <p class="text-sm text-slate-200 max-w-xl">
                            {{ $user->bio }}
                        </p>
                    @endif

                    {{-- Site web / Twitter / Instagram --}}
                    <div class="flex flex-wrap items-center gap-2 text-xs">
                        @if(!empty($user->website))
                            <a href="{{ $user->website }}" target="_blank"
                               class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-slate-800 text-slate-100 hover:bg-slate-700">
                                🌐 <span class="ml-1 truncate max-w-[180px]">{{ $user->website }}</span>
                            </a>
                        @endif

                        @if(!empty($user->twitter))
                            <a href="{{ Str::startsWith($user->twitter, ['http://', 'https://']) ? $user->twitter : 'https://twitter.com/'.$user->twitter }}"
                               target="_blank"
                               class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-slate-800 text-slate-100 hover:bg-slate-700">
                                𝕏 <span class="ml-1">{{ ltrim($user->twitter, '@') }}</span>
                            </a>
                        @endif

                        @if(!empty($user->instagram))
                            <a href="{{ Str::startsWith($user->instagram, ['http://', 'https://']) ? $user->instagram : 'https://instagram.com/'.$user->instagram }}"
                               target="_blank"
                               class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-slate-800 text-slate-100 hover:bg-slate-700">
                                📸 <span class="ml-1">{{ ltrim($user->instagram, '@') }}</span>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Stats --}}
                <div class="flex flex-row sm:flex-col gap-4 text-sm">
                    <div class="text-center sm:text-right">
                        <div class="text-lg font-semibold">{{ $totalPosts }}</div>
                        <div class="text-xs uppercase tracking-wide text-slate-300">
                            Post{{ $totalPosts > 1 ? 's' : '' }}
                        </div>
                    </div>

                    <div class="text-center sm:text-right">
                        <div class="text-lg font-semibold">{{ $totalComments }}</div>
                        <div class="text-xs uppercase tracking-wide text-slate-300">
                            Commentaires
                        </div>
                    </div>

                    <div class="text-center sm:text-right">
                        <div class="text-lg font-semibold">{{ $totalLikes }}</div>
                        <div class="text-xs uppercase tracking-wide text-slate-300">
                            Likes
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mes posts --}}
            <div class="bg-white shadow-sm sm:rounded-xl p-6 sm:p-7">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-900">
                        Mes posts
                    </h3>
                    <a href="{{ route('posts.index') }}"
                       class="text-xs text-emerald-600 hover:text-emerald-700">
                        Voir le fil complet →
                    </a>
                </div>

                @if($posts->count())
                    <div class="space-y-4">
                        @foreach($posts as $post)
                            <article
                                onclick="window.location='{{ route('posts.show', $post) }}'"
                                class="cursor-pointer border border-slate-100 rounded-lg px-4 py-3 hover:shadow-sm hover:-translate-y-0.5 transition">

                                <div class="flex items-start gap-3">
                                    {{-- mini avatar auteur --}}
                                    @if(!empty($user->avatar_path))
                                        <img src="{{ asset('storage/'.$user->avatar_path) }}"
                                             alt="Avatar"
                                             class="hidden sm:block h-9 w-9 rounded-full object-cover">
                                    @else
                                        <div class="hidden sm:flex h-9 w-9 rounded-full bg-emerald-100 text-emerald-700 items-center justify-center text-sm font-semibold">
                                            {{ $initial }}
                                        </div>
                                    @endif

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-baseline justify-between gap-2">
                                            <h4 class="font-semibold text-slate-900 text-sm sm:text-base">
                                                {{ $post->titre }}
                                            </h4>
                                            <span class="text-xs text-slate-400">
                                                {{ $post->created_at->diffForHumans() }}
                                            </span>
                                        </div>

                                        @if($post->texte)
                                            <p class="mt-1 text-xs sm:text-sm text-slate-600">
                                                {{ Str::limit($post->texte, 160) }}
                                            </p>
                                        @endif

                                        <div class="mt-2 flex flex-wrap items-center gap-3 text-[11px] text-slate-500">
                                            @if($post->url)
                                                <span class="truncate max-w-xs">
                                                    🔗 {{ $post->url }}
                                                </span>
                                            @endif

                                            @if($post->community)
                                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-medium text-slate-600">
                                                    {{ $post->community->name }}
                                                </span>
                                            @endif

                                            <span class="inline-flex items-center gap-1">
                                                👍 {{ $post->likes_count ?? 0 }}
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
                    <p class="text-sm text-slate-500">
                        Tu n’as encore posté aucun contenu.
                    </p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
