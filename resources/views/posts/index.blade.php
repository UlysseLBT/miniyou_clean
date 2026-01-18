@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Posts') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-5">
                <a href="{{ route('posts.create') }}"
                   class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium
                          bg-white/5 border border-red-500/40 text-white
                          hover:bg-white/10 transition
                          shadow-[0_0_0_1px_rgba(239,68,68,.20)]">
                    + Nouveau post
                </a>
            </div>

            @if (session('status'))
                <div class="mb-4 rounded-2xl border border-white/10 bg-neutral-950/35 backdrop-blur px-4 py-3 text-sm text-neutral-200">
                    {{ session('status') }}
                </div>
            @endif

            <div class="space-y-4">
                @forelse ($posts as $post)
                    @php
                        $user = $post->user;

                        $displayName = $user?->name ?? $user?->username ?? 'Utilisateur';
                        $initial     = $user ? Str::upper(Str::substr($displayName, 0, 1)) : 'U';

                        $avatarPath = $user?->avatar_path;
                        $avatarUrl  = null;

                        if ($avatarPath) {
                            $avatarUrl = Str::startsWith($avatarPath, ['http://', 'https://'])
                                ? $avatarPath
                                : Storage::disk('public')->url($avatarPath);
                        }

                        $host          = $post->url ? parse_url($post->url, PHP_URL_HOST) : null;
                        $likesCount    = $post->likes_count ?? 0;
                        $commentsCount = $post->comments_count ?? 0;

                        $goTo = route('posts.show', ['post' => $post->id, 'page' => $posts->currentPage()]);
                    @endphp

                    <article
                        role="link"
                        tabindex="0"
                        onclick="window.location='{{ $goTo }}'"
                        onkeydown="if(event.key==='Enter' || event.key===' '){ window.location='{{ $goTo }}' }"
                        class="cursor-pointer bg-neutral-950/35 border border-white/10 backdrop-blur rounded-2xl
                               shadow-[0_10px_35px_rgba(0,0,0,.35)] p-4 sm:p-5
                               hover:bg-neutral-950/45 hover:border-white/20 hover:-translate-y-0.5 transition
                               focus:outline-none focus:ring-2 focus:ring-red-500/30">

                        <div class="flex items-start gap-4">
                            <div class="flex h-10 w-10 flex-none items-center justify-center rounded-full overflow-hidden border border-white/10 bg-white/5">
                                @if($avatarUrl)
                                    <img src="{{ $avatarUrl }}" alt="Avatar de {{ $displayName }}"
                                         class="h-10 w-10 object-cover" loading="lazy">
                                @else
                                    <div class="h-10 w-10 flex items-center justify-center rounded-full text-neutral-200 font-semibold">
                                        {{ $initial }}
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-baseline justify-between gap-2">
                                    <h3 class="text-base sm:text-lg font-semibold text-white">
                                        {{ $post->titre }}
                                    </h3>

                                    <div class="text-xs text-neutral-400 text-right">
                                        @if ($user)
                                            <span class="font-medium text-neutral-300">{{ $displayName }}</span>
                                            <span class="mx-1">·</span>
                                        @endif
                                        {{ $post->created_at->diffForHumans() }}
                                    </div>
                                </div>

                                @if ($post->texte)
                                    <p class="mt-1 text-sm text-neutral-300">
                                        {{ Str::limit($post->texte, 180) }}
                                    </p>
                                @endif

                                @if ($post->url)
                                    <div class="mt-2 flex flex-wrap items-center gap-2">
                                        <a href="{{ $post->url }}"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           onclick="event.stopPropagation();"
                                           class="text-sm text-red-300 hover:text-red-200 hover:underline break-all">
                                            {{ $post->url }}
                                        </a>

                                        @if ($host)
                                            <span class="inline-flex items-center rounded-full bg-white/5 border border-white/10 px-2 py-0.5
                                                         text-[11px] font-medium text-neutral-300"
                                                  onclick="event.stopPropagation();">
                                                {{ $host }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <div class="mt-3 flex items-center gap-4 text-xs text-neutral-400">
                                    <span class="inline-flex items-center gap-1">
                                        ❤️ <span>{{ $likesCount }}</span>
                                    </span>

                                    <span class="inline-flex items-center gap-1">
                                        💬 <span>{{ $commentsCount }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="bg-neutral-950/35 border border-white/10 backdrop-blur rounded-2xl shadow-lg p-6 text-center text-neutral-300">
                        Aucun post pour le moment.<br>
                        <a href="{{ route('posts.create') }}" class="text-red-300 hover:text-red-200 hover:underline">
                            Crée ton premier post
                        </a>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
