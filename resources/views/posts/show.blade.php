@php
    use Illuminate\Support\Str;

    $user = $post->user;
    $initial = $user ? Str::upper(Str::substr($user->name, 0, 1)) : 'U';
    $host = $post->url ? parse_url($post->url, PHP_URL_HOST) : null;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Détails du post
            </h2>

            <a href="{{ route('posts.index') }}"
               class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium
                      bg-gray-100 text-gray-700 hover:bg-gray-200">
                ← Retour aux posts
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

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

            {{-- Actions en bas --}}
            @can('delete', $post)
                <div class="mt-4 flex justify-end">
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
