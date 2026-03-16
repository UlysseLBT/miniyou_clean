@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-6 px-4">

    {{-- En-tête du hashtag --}}
    <div class="mb-6 p-4 bg-blue-50 border border-blue-100 rounded-xl">
        <h1 class="text-2xl font-semibold text-blue-700">#{{ $hashtag->name }}</h1>
        <p class="text-sm text-gray-500 mt-1">
            {{ $posts->total() }} post{{ $posts->total() > 1 ? 's' : '' }}
        </p>
    </div>

    {{-- Liste des posts --}}
    @forelse($posts as $post)
        <div class="bg-white border border-gray-200 rounded-xl p-4 mb-4 shadow-sm">

            {{-- Auteur --}}
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('profile.show', $post->user) }}"
                   class="font-medium text-gray-800 hover:underline">
                    {{ $post->user->name }}
                </a>
                <span class="text-xs text-gray-400">
                    {{ $post->created_at->diffForHumans() }}
                </span>
            </div>

            {{-- Contenu --}}
            <p class="text-gray-700 text-sm leading-relaxed">
                {!! e($post->content) !!}
            </p>

            {{-- Hashtags --}}
            @if($post->hashtags->isNotEmpty())
                <div class="flex flex-wrap gap-1 mt-2">
                    @foreach($post->hashtags as $tag)
                        <a href="{{ route('hashtag.show', $tag->name) }}"
                           class="text-xs text-blue-500 hover:text-blue-700 font-medium
                                  {{ $tag->name === $hashtag->name ? 'bg-blue-100 px-2 py-0.5 rounded-full' : '' }}">
                            #{{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    @empty
        <p class="text-center text-gray-400 py-10">Aucun post pour ce hashtag.</p>
    @endforelse

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $posts->links() }}
    </div>

</div>
@endsection