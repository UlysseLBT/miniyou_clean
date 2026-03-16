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
                                        $label = match($report->status) {
                                            'pending'   => 'En attente',
                                            'reviewed'  => 'Traité',
                                            'dismissed' => 'Ignoré',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-[11px] font-medium {{ $badge }}">
                                        {{ $label }}
                                    </span>

                                    {{-- Raison --}}
                                    <span class="inline-flex items-center rounded-full border border-red-500/30 bg-red-500/10 px-2.5 py-0.5 text-[11px] font-medium text-red-300">
                                        {{ \App\Models\Report::REASONS[$report->reason] }}
                                    </span>
                                </div>

                                {{-- Post signalé --}}
                                <p class="text-sm font-medium text-white truncate">
                                    Post : "{{ $report->post?->titre ?? 'Post supprimé' }}"
                                </p>

                                <p class="text-xs text-neutral-400 mt-0.5">
                                    Signalé par <span class="text-neutral-300">{{ $report->user?->name ?? 'Utilisateur supprimé' }}</span>
                                    · {{ $report->created_at->diffForHumans() }}
                                </p>
                            </div>

                            {{-- Actions --}}
                            @if($report->status === 'pending')
                                <div class="flex gap-2 shrink-0">
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
                                </div>
                            @endif
                        </div>
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