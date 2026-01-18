<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full bg-white/5 border border-white/10 px-3 py-1 text-xs text-neutral-300">
                    <span class="h-2 w-2 rounded-full bg-red-500/80"></span>
                    <span>MiniYou</span>
                    <span class="opacity-60">·</span>
                    <span>Nouvelle communauté</span>
                </div>

                <h2 class="mt-2 font-semibold text-2xl text-white leading-tight">
                    Créer une communauté
                </h2>

                <p class="mt-1 text-sm text-neutral-400">
                    Choisis un nom, ajoute une description, et définis la visibilité.
                </p>
            </div>

            <a href="{{ route('communities.index') }}"
               class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium
                      bg-white/5 border border-white/10 text-neutral-200
                      hover:bg-white/10 transition">
                Annuler
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-neutral-950/35 border border-white/10 backdrop-blur rounded-2xl
                        shadow-[0_10px_35px_rgba(0,0,0,.35)] p-6 sm:p-7">

                <form method="POST" action="{{ route('communities.store') }}" class="space-y-6">
                    @csrf

                    {{-- Nom --}}
                    <div>
                        <label class="block text-sm font-medium text-neutral-200 mb-2" for="name">
                            Nom de la communauté <span class="text-red-400">*</span>
                        </label>

                        <input id="name" name="name" type="text" required
                               value="{{ old('name') }}"
                               placeholder="Ex: One Piece Fans"
                               class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-neutral-100
                                      placeholder:text-neutral-500
                                      focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-red-500/30">

                        @error('name')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-sm font-medium text-neutral-200 mb-2" for="description">
                            Description
                        </label>

                        <textarea id="description" name="description" rows="4"
                                  placeholder="Décris le but de ta communauté…"
                                  class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-neutral-100
                                         placeholder:text-neutral-500
                                         focus:outline-none focus:ring-2 focus:ring-red-500/30 focus:border-red-500/30">{{ old('description') }}</textarea>

                        @error('description')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Visibilité --}}
                    <div>
                        <label class="block text-sm font-medium text-neutral-200 mb-2">
                            Visibilité
                        </label>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <label class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-3 hover:bg-white/10 transition cursor-pointer">
                                <input type="radio" name="visibility" value="public"
                                       class="rounded border-white/20 bg-transparent text-red-500 focus:ring-red-500/30"
                                       {{ old('visibility', 'public') === 'public' ? 'checked' : '' }}>
                                <div class="min-w-0">
                                    <div class="text-sm font-medium text-neutral-100">Publique</div>
                                    <div class="text-xs text-neutral-400">Tout le monde peut voir et rejoindre.</div>
                                </div>
                            </label>

                            <label class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-4 py-3 hover:bg-white/10 transition cursor-pointer">
                                <input type="radio" name="visibility" value="private"
                                       class="rounded border-white/20 bg-transparent text-red-500 focus:ring-red-500/30"
                                       {{ old('visibility') === 'private' ? 'checked' : '' }}>
                                <div class="min-w-0">
                                    <div class="text-sm font-medium text-neutral-100">Privée</div>
                                    <div class="text-xs text-neutral-400">Visible, mais accès réservé (sur demande).</div>
                                </div>
                            </label>
                        </div>

                        @error('visibility')
                            <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3">
                        <a href="{{ route('communities.index') }}"
                           class="inline-flex justify-center items-center rounded-full px-6 py-2.5 text-sm font-medium
                                  bg-white/5 border border-white/10 text-neutral-200
                                  hover:bg-white/10 transition">
                            Annuler
                        </a>

                        <button type="submit"
                                class="inline-flex justify-center items-center rounded-full px-6 py-2.5 text-sm font-medium
                                       bg-white/5 border border-red-500/40 text-white
                                       hover:bg-white/10 transition
                                       shadow-[0_0_0_1px_rgba(239,68,68,.18)]">
                            Créer
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
