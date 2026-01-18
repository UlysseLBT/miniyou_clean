<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full bg-white/5 border border-white/10 px-3 py-1 text-xs text-neutral-300">
                    <span class="h-2 w-2 rounded-full bg-red-500/80"></span>
                    <span>MiniYou</span>
                    <span class="opacity-60">·</span>
                    <span>Communautés</span>
                </div>

                <h2 class="mt-2 font-semibold text-2xl text-white leading-tight">
                    Communautés
                </h2>
                <p class="mt-1 text-sm text-neutral-400">
                    Découvre des communautés et rejoins celles qui t’intéressent.
                </p>
            </div>

            <a href="{{ route('communities.create') }}"
               class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium
                      bg-white/5 border border-red-500/40 text-white
                      hover:bg-white/10 transition
                      shadow-[0_0_0_1px_rgba(239,68,68,.18)]">
                + Créer une communauté
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Liste --}}
            @if($communities->count())
                <div class="space-y-4">
                    @foreach($communities as $community)
                        <a href="{{ route('communities.show', $community) }}"
                           class="group block bg-neutral-950/35 border border-white/10 backdrop-blur rounded-2xl
                                  shadow-[0_10px_35px_rgba(0,0,0,.30)] p-5
                                  hover:bg-neutral-950/45 hover:border-white/20 hover:-translate-y-0.5 transition
                                  focus:outline-none focus:ring-2 focus:ring-red-500/30">

                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="font-semibold text-white text-base sm:text-lg truncate">
                                            {{ $community->name }}
                                        </h3>

                                        @if($community->visibility === 'private')
                                            <span class="inline-flex items-center rounded-full bg-white/5 border border-white/10 px-2 py-0.5
                                                         text-[11px] font-medium text-neutral-200">
                                                Privée
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-white/5 border border-white/10 px-2 py-0.5
                                                         text-[11px] font-medium text-neutral-300">
                                                Publique
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mt-1 text-sm text-neutral-400">
                                        par <span class="text-neutral-200 font-medium">
                                            {{ $community->owner->display_name ?? $community->owner->name }}
                                        </span>
                                    </div>

                                    @if($community->description)
                                        <p class="mt-3 text-sm text-neutral-300 line-clamp-2">
                                            {{ $community->description }}
                                        </p>
                                    @else
                                        <p class="mt-3 text-sm text-neutral-500 italic">
                                            Aucune description.
                                        </p>
                                    @endif
                                </div>

                                <div class="flex-none">
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium
                                                 bg-white/5 border border-white/10 text-neutral-200
                                                 group-hover:border-red-500/30 group-hover:text-white transition">
                                        Voir →
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $communities->links() }}
                </div>
            @else
                <div class="bg-neutral-950/35 border border-white/10 backdrop-blur rounded-2xl p-6 text-center
                            shadow-[0_10px_35px_rgba(0,0,0,.25)]">
                    <p class="text-neutral-300">
                        Aucune communauté pour l’instant.
                    </p>

                    <a href="{{ route('communities.create') }}"
                       class="mt-4 inline-flex items-center rounded-full px-4 py-2 text-sm font-medium
                              bg-white/5 border border-red-500/40 text-white
                              hover:bg-white/10 transition
                              shadow-[0_0_0_1px_rgba(239,68,68,.18)]">
                        + Créer la première communauté
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
