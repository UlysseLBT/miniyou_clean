<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Communautés
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Toutes les communautés</h3>

                        <a href="{{ route('communities.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                            + Créer une communauté
                        </a>
                    </div>

                    @if($communities->count())
                        <div class="space-y-4">
                            @foreach($communities as $community)
                                <a href="{{ route('communities.show', $community) }}"
                                   class="block border rounded-lg px-4 py-3 hover:bg-gray-50">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <div class="font-semibold">
                                                {{ $community->name }}
                                                @if($community->visibility === 'private')
                                                    <span class="ml-2 text-xs px-2 py-0.5 rounded-full bg-gray-200 text-gray-700">
                                                        Privée
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                par {{ $community->owner->display_name ?? $community->owner->name }}
                                            </div>
                                            @if($community->description)
                                                <p class="mt-1 text-sm text-gray-700 line-clamp-2">
                                                    {{ $community->description }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            {{ $communities->links() }}
                        </div>
                    @else
                        <p class="text-gray-600">Aucune communauté pour l’instant.</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
