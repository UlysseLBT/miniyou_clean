<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Communauté privée</h2>
    </x-slot>

    <div class="max-w-3xl mx-auto p-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border">
            <p class="text-slate-700">
                Cette communauté est privée. Seuls les membres peuvent voir le contenu.
            </p>

            @if($pending)
                <p class="mt-4 text-amber-600 font-medium">Demande en attente…</p>

                <form method="POST" action="{{ route('communities.joinRequests.cancel', $community) }}" class="mt-3">
                    @csrf
                    <button class="px-4 py-2 rounded-lg bg-slate-100 hover:bg-slate-200">
                        Annuler la demande
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('communities.joinRequests.store', $community) }}" class="mt-4">
                    @csrf
                    <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">
                        Demander à rejoindre
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
