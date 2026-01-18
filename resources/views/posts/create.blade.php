<x-app-layout>
    {{-- Header cohérent dark --}}
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full bg-white/5 border border-white/10 px-3 py-1 text-xs text-neutral-300">
                    <span class="h-2 w-2 rounded-full bg-red-500/80"></span>
                    <span>MiniYou</span>
                    <span class="opacity-60">·</span>
                    <span>Nouveau post</span>
                </div>

                <h2 class="mt-2 font-semibold text-2xl text-white leading-tight">
                    Créer un nouveau post
                </h2>

                <p class="mt-1 text-sm text-neutral-400">
                    Ajoute un titre, un texte et un lien si tu veux.
                </p>
            </div>

            <a href="{{ route('posts.index') }}"
               class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium
                      bg-white/5 border border-white/10 text-neutral-200
                      hover:bg-white/10 transition">
                Annuler
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Message de statut --}}
            @if (session('status'))
                <div class="mb-4 rounded-2xl border border-white/10 bg-neutral-950/35 backdrop-blur px-4 py-3 text-sm text-neutral-200">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Formulaire --}}
            <div class="bg-neutral-950/35 border border-white/10 backdrop-blur rounded-2xl
                        shadow-[0_10px_35px_rgba(0,0,0,.35)] p-6 sm:p-7">

                <form method="POST" action="{{ route('posts.store') }}" class="space-y-5">
                    @csrf

                    {{-- Titre --}}
                    <div>
                        <label for="titre" class="block text-sm font-medium text-neutral-200 mb-2">
                            Titre <span class="text-red-400">*</span>
                        </label>

                        <input type="text" name="titre" id="titre" required
                               value="{{ old('titre') }}"
                               placeholder="Ex: Un super lien..."
                               class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-neutral-100
                                      placeholder:text-neutral-500
                                      focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-red-500/30">

                        @error('titre')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Texte --}}
                    <div>
                        <label for="texte" class="block text-sm font-medium text-neutral-200 mb-2">
                            Texte
                        </label>

                        <textarea name="texte" id="texte" rows="6"
                                  placeholder="Écris quelque chose…"
                                  class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-neutral-100
                                         placeholder:text-neutral-500
                                         focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-red-500/30">{{ old('texte') }}</textarea>

                        @error('texte')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- URL --}}
                    <div>
                        <label for="url" class="block text-sm font-medium text-neutral-200 mb-2">
                            URL
                        </label>

                        <input type="url" name="url" id="url"
                               value="{{ old('url') }}"
                               placeholder="https://..."
                               class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-neutral-100
                                      placeholder:text-neutral-500
                                      focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-red-500/30">

                        @error('url')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Community hidden --}}
                    @if (isset($community))
                        <input type="hidden" name="community_id" value="{{ $community->id }}">
                    @endif

                    {{-- Boutons --}}
                    <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        <button type="submit"
                                class="inline-flex justify-center items-center rounded-full px-6 py-2.5 text-sm font-medium
                                       bg-white/5 border border-red-500/40 text-white
                                       hover:bg-white/10 transition
                                       shadow-[0_0_0_1px_rgba(239,68,68,.18)]">
                            Publier
                        </button>

                        <a href="{{ route('posts.index') }}"
                           class="inline-flex justify-center items-center rounded-full px-6 py-2.5 text-sm font-medium
                                  bg-white/5 border border-white/10 text-neutral-200
                                  hover:bg-white/10 transition">
                            Retour
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
