<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-white leading-tight">
                Signalements
            </h2>
            <span class="text-xs text-neutral-400">{{ $reports->total() }} signalement(s)</span>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('status'))
                <div class="mb-4 rounded-2xl border border-white/10 bg-neutral-950/35 backdrop-blur px-4 py-3 text-sm text-neutral-200">
                    {{ session('status') }}
                </div>
            @endif

            <div class="space-y-3">
                @forelse($reports as $report)
                    <div class="bg-neutral-950/35 border border-white/10 backdrop-blur rounded-2xl p-4 sm:p-5">

                        <div class="flex flex-wrap items-start justify-between gap-3">

                            {{-- Infos du signalement --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-1">

                                    {{-- Statut --}}
                                    @php
                                        $badge = match($report->status) {
                                            'pending'   => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
                                            'reviewed'  => 'bg-green-500/20 text-green-300 border-green-500/30',
                                            'dismissed' => 'bg-neutral-500/20 text-neutral-400 border-neutral-500/30',
                                        };
                                        $statusLabel = match($report->status) {
                                            'pending'   => 'En attente',
                                            'reviewed'  => 'Traité',
                                            'dismissed' => 'Ignoré',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-[11px] font-medium {{ $badge }}">
                                        {{ $statusLabel }}
                                    </span>

                                    {{-- Type : post ou commentaire --}}
                                    <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-2.5 py-0.5 text-[11px] font-medium text-neutral-400">
                                        {{ $report->comment_id ? 'Commentaire' : 'Post' }}
                                    </span>

                                    {{-- Raison --}}
                                    <span class="inline-flex items-center rounded-full border border-red-500/30 bg-red-500/10 px-2.5 py-0.5 text-[11px] font-medium text-red-300">
                                        {{ \App\Models\Report::REASONS[$report->reason] }}
                                    </span>
                                </div>

                                {{-- Auteur du signalement --}}
                                <p class="text-xs text-neutral-400 mt-0.5">
                                    Signalé par <span class="text-neutral-300">{{ $report->user?->name ?? 'Utilisateur supprimé' }}</span>
                                    · {{ $report->created_at->diffForHumans() }}
                                </p>
                            </div>

                            {{-- Actions --}}
                            @if($report->status === 'pending')
                                <div class="flex flex-wrap gap-2 shrink-0">
                                    <form method="POST" action="{{ route('admin.reports.update', $report) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="reviewed">
                                        <button type="submit"
                                                class="rounded-full px-3 py-1.5 text-xs font-medium
                                                       bg-green-500/10 border border-green-500/30 text-green-300
                                                       hover:bg-green-500/20 transition">
                                            ✓ Traité
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.reports.update', $report) }}">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="dismissed">
                                        <button type="submit"
                                                class="rounded-full px-3 py-1.5 text-xs font-medium
                                                       bg-white/5 border border-white/10 text-neutral-400
                                                       hover:bg-white/10 transition">
                                            ✕ Ignorer
                                        </button>
                                    </form>

                                    {{-- Supprimer le post --}}
                                    @if($report->post)
                                        <form method="POST" action="{{ route('admin.reports.delete-post', $report) }}"
                                              onsubmit="return confirm('Supprimer ce post définitivement ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="rounded-full px-3 py-1.5 text-xs font-medium
                                                           bg-red-500/10 border border-red-500/30 text-red-300
                                                           hover:bg-red-500/20 transition">
                                                🗑 Supprimer le post
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Supprimer le commentaire --}}
                                    @if($report->comment)
                                        <form method="POST" action="{{ route('admin.reports.delete-comment', $report) }}"
                                              onsubmit="return confirm('Supprimer ce commentaire définitivement ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="rounded-full px-3 py-1.5 text-xs font-medium
                                                           bg-red-500/10 border border-red-500/30 text-red-300
                                                           hover:bg-red-500/20 transition">
                                                🗑 Supprimer le commentaire
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- Contenu signalé : commentaire OU post --}}
                        @if($report->comment)
                            <div class="mt-3 rounded-xl border border-white/5 bg-white/5 px-4 py-3 space-y-1">
                                <p class="text-xs text-neutral-500 uppercase tracking-wide">
                                    Commentaire de <span class="text-neutral-300">{{ $report->comment->user?->name ?? 'Auteur inconnu' }}</span>
                                </p>
                                <p class="text-sm text-neutral-300">{{ $report->comment->body }}</p>
                            </div>
                        @elseif($report->post)
                            <div class="mt-3 rounded-xl border border-white/5 bg-white/5 px-4 py-3 space-y-1">
                                <p class="text-xs text-neutral-500 uppercase tracking-wide">
                                    Post de <span class="text-neutral-300">{{ $report->post->user?->name ?? 'Auteur inconnu' }}</span>
                                </p>
                                @if($report->post->titre)
                                    <p class="text-sm font-medium text-white">{{ $report->post->titre }}</p>
                                @endif
                                @if($report->post->texte)
                                    <p class="text-sm text-neutral-300">{{ $report->post->texte }}</p>
                                @endif
                                @if($report->post->url)
                                    <a href="{{ $report->post->url }}" target="_blank"
                                       class="block text-xs text-blue-400 hover:underline truncate">
                                        {{ $report->post->url }}
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="mt-3 rounded-xl border border-white/5 bg-white/5 px-4 py-3">
                                <p class="text-xs text-neutral-500 italic">Contenu déjà supprimé.</p>
                            </div>
                        @endif

                    </div>
                @empty
                    <div class="bg-neutral-950/35 border border-white/10 backdrop-blur rounded-2xl p-8 text-center text-neutral-400">
                        Aucun signalement pour le moment.
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
</x-app-layout>