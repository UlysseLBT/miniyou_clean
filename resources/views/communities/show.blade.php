@php
    use Illuminate\Support\Str;

    $isOwner  = auth()->check() && auth()->id() === $community->owner_id;
    $isMember = auth()->check() && $community->members->contains(auth()->id());
    $canPost  = $isOwner || $isMember; // créateur OU membre
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $community->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Infos communauté --}}
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 flex justify-between items-start gap-4">
                    <div>
                        <h3 class="text-lg font-semibold">
                            {{ $community->name }}
                            @if($community->visibility === 'private')
                                <span class="ml-2 text-xs px-2 py-0.5 rounded-full bg-gray-200 text-gray-700">
                                    Privée
                                </span>
                            @endif
                        </h3>

                        <p class="text-sm text-gray-500">
                            Créée par {{ $community->owner->display_name ?? $community->owner->name }}
                        </p>

                        @if($community->description)
                            <p class="mt-3 text-gray-700">
                                {{ $community->description }}
                            </p>
                        @endif
                    </div>

                    {{-- Actions à droite --}}
                    @auth
                        <div class="flex flex-col items-end gap-2">
                            {{-- Bouton créer un post : pour le créateur ET les membres --}}
                            @if ($canPost)
                                <a href="{{ route('communities.posts.create', $community) }}"
                                   class="px-4 py-2 text-sm rounded-full bg-emerald-500 text-white hover:bg-emerald-600">
                                    + Nouveau post dans cette communauté
                                </a>
                            @endif

                            {{-- Gestion créateur / membres --}}
                            @if ($isOwner)
                                {{-- Supprimer la communauté (seulement créateur) --}}
                                <form method="POST"
                                      action="{{ route('communities.destroy', $community) }}"
                                      onsubmit="return confirm('Supprimer définitivement cette communauté et tous ses posts ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-4 py-2 text-sm rounded-full bg-red-500 text-white hover:bg-red-600">
                                        Supprimer la communauté
                                    </button>
                                </form>
                            @else
                                {{-- Rejoindre / quitter pour les autres --}}
                                @if ($isMember)
                                    <form method="POST" action="{{ route('communities.leave', $community) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-4 py-2 text-sm rounded-md border border-red-600 text-red-600 hover:bg-red-50">
                                            Quitter la communauté
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('communities.join', $community) }}">
                                        @csrf
                                        <button type="submit"
                                                class="px-4 py-2 text-sm rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                                            Rejoindre la communauté
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    @endauth
                </div>
            </div>

            {{-- Liste des posts de la communauté --}}
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Posts de la communauté</h3>

                    @if($posts->count())
                        <div class="space-y-4">
                            @foreach($posts as $post)
                                <article
                                    onclick="window.location='{{ route('posts.show', $post) }}'"
                                    class="cursor-pointer border rounded-lg px-4 py-3 hover:shadow-sm hover:-translate-y-0.5 transition">

                                    <div class="text-xs text-gray-500 mb-1 flex justify-between">
                                        <span>
                                            Posté par {{ $post->user->display_name ?? $post->user->name }}
                                        </span>
                                        <span>
                                            {{ $post->created_at->diffForHumans() }}
                                        </span>
                                    </div>

                                    <div class="font-semibold text-sm sm:text-base text-gray-900">
                                        {{ $post->titre }}
                                    </div>

                                    @if($post->texte)
                                        <p class="mt-1 text-sm text-gray-700">
                                            {{ Str::limit($post->texte, 200) }}
                                        </p>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600">
                            Aucun post dans cette communauté pour l’instant.
                        </p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
