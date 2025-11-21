<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Créer une communauté
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('communities.store') }}" class="space-y-6">
                        @csrf

                        {{-- Nom --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700" for="name">
                                Nom de la communauté
                            </label>
                            <input id="name" name="name" type="text"
                                   value="{{ old('name') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700" for="description">
                                Description
                            </label>
                            <textarea id="description" name="description" rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Visibilité --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Visibilité
                            </label>
                            <div class="mt-2 flex gap-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="visibility" value="public"
                                           class="rounded border-gray-300"
                                           {{ old('visibility', 'public') === 'public' ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700">Publique</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="visibility" value="private"
                                           class="rounded border-gray-300"
                                           {{ old('visibility') === 'private' ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700">Privée</span>
                                </label>
                            </div>
                            @error('visibility')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('communities.index') }}"
                               class="px-4 py-2 border rounded-md text-sm text-gray-700 hover:bg-gray-50">
                                Annuler
                            </a>
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-700">
                                Créer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
