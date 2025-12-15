<x-app-layout>
    {{-- On laisse le header vide pour ne rien rajouter dans la barre blanche --}}
    <x-slot name="header"></x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Message de statut --}}
            @if (session('status'))
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Formulaire de création --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-2xl font-bold text-slate-900 mb-6">Créer un nouveau post</h2>

                <form method="POST" action="{{ route('posts.store') }}">
                    @csrf

                    {{-- Titre --}}
                    <div class="mb-4">
                        <label for="titre" class="block text-sm font-medium text-slate-700 mb-2">
                            Titre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="titre" id="titre" required
                               class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                               value="{{ old('titre') }}">
                        @error('titre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Texte --}}
                    <div class="mb-4">
                        <label for="texte" class="block text-sm font-medium text-slate-700 mb-2">
                            Texte
                        </label>
                        <textarea name="texte" id="texte" rows="5"
                                  class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('texte') }}</textarea>
                        @error('texte')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- URL --}}
                    <div class="mb-6">
                        <label for="url" class="block text-sm font-medium text-slate-700 mb-2">
                            URL
                        </label>
                        <input type="url" name="url" id="url"
                               class="w-full rounded-lg border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                               value="{{ old('url') }}">
                        @error('url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Boutons --}}
                    <div class="flex items-center gap-3">
                        <button type="submit"
                                class="inline-flex items-center rounded-full px-6 py-2 text-sm font-medium
                                       bg-emerald-500 text-white hover:bg-emerald-600 shadow-sm transition">
                            Publier
                        </button>
                        <a href="{{ route('posts.index') }}"
                           class="text-sm text-slate-600 hover:text-slate-900">
                            Annuler
                        </a>
                    </div>
                    @if (isset($community))
                        <input type="hidden" name="community_id" value="{{ $community->id }}">
                    @endif
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
