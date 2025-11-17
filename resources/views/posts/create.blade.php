<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Créer un nouveau post
            </h2>

            <a href="{{ route('posts.index') }}"
               class="inline-flex items-center rounded border px-3 py-1 bg-gray-200 text-gray-800 hover:bg-gray-300">
                ← Retour à la liste
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="mb-4 rounded bg-red-100 p-3 text-red-800">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('posts.store') }}" class="space-y-6">
                    @csrf

                    {{-- Titre --}}
                    <div>
                        <label for="titre" class="block text-sm font-medium text-gray-700">
                            Titre
                        </label>
                        <input
                            type="text"
                            name="titre"
                            id="titre"
                            value="{{ old('titre') }}"
                            class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                            required
                        >
                        @error('titre')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="texte" class="block text-sm font-medium text-gray-700">
                            Description
                        </label>
                        <textarea
                            name="texte"
                            id="texte"
                            rows="4"
                            class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                        >{{ old('texte') }}</textarea>
                        @error('texte')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- URL --}}
                    <div>
                        <label for="url" class="block text-sm font-medium text-gray-700">
                            URL
                        </label>
                        <input
                            type="url"
                            name="url"
                            id="url"
                            value="{{ old('url') }}"
                            class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                            required
                        >
                        @error('url')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- BOUTONS --}}
                    <div class="flex items-center justify-end space-x-3">
                        <a href="{{ route('posts.index') }}"
                           class="text-sm text-gray-600 hover:text-gray-900">
                            Annuler
                        </a>

                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-gray-900 uppercase tracking-widest hover:bg-red-600 focus:bg-red-600 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            Créer le post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
