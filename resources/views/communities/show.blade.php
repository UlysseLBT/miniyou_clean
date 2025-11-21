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

                    {{-- Bouton rejoindre / quitter (on code la logique dans un second temps) --}}
                    @auth
                        @php
                            $isMember = $community->members->contains(auth()->id());
                        @endphp

                        <div>
                            @if($isMember)
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
                        </div>
                    @endauth
                </div>
            </div>

            {{-- Liste des posts de la communauté (on branchera avec tes posts ensuite) --}}
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Posts de la communauté</h3>

                    @if($community->posts->count())
                        <div class="space-y-4">
                            @foreach($community->posts as $post)
                                <div class="border rounded-lg px-4 py-3">
                                    <div class="text-sm text-gray-500 mb-1">
                                        Posté par {{ $post->user->display_name ?? $post->user->name }}
                                        • {{ $post->created_at->diffForHumans() }}
                                    </div>
                                    <div class="font-semibold">{{ $post->title ?? 'Post #' . $post->id }}</div>
                                    @if($post->content ?? false)
                                        <p class="mt-1 text-sm text-gray-700">
                                            {{ Str::limit($post->content, 200) }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600">Aucun post dans cette communauté pour l’instant.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
