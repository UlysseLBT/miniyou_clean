<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="min-h-screen bg-[#050506] text-neutral-100 py-10">
        <div class="max-w-2xl mx-auto px-4 space-y-4">

            <h1 class="text-2xl font-bold text-white mb-6">🔔 Notifications</h1>

            @forelse($notifications as $notif)
                @php
                    $data = $notif->data;
                    $icon = match($notif->type) {
                        'new_follower'   => '👤',
                        'post_liked'     => '❤️',
                        'post_commented' => '💬',
                        default          => '🔔',
                    };
                    $message = match($notif->type) {
                        'new_follower'   => "<strong>{$data['follower_name']}</strong> vous suit maintenant.",
                        'post_liked'     => "<strong>{$data['liker_name']}</strong> a aimé votre post <em>« {$data['post_titre']} »</em>.",
                        'post_commented' => "<strong>{$data['commenter_name']}</strong> a commenté <em>« {$data['post_titre']} »</em>.",
                        default          => 'Nouvelle notification.',
                    };
                @endphp

                <a href="{{ $data['url'] ?? '#' }}"
                   class="flex items-start gap-4 rounded-2xl border px-5 py-4 backdrop-blur transition
                          {{ $notif->isRead()
                              ? 'border-white/10 bg-neutral-950/35 hover:bg-white/5'
                              : 'border-red-500/30 bg-red-950/20 hover:bg-red-950/30' }}">
                    <span class="text-xl mt-0.5">{{ $icon }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-neutral-200">{!! $message !!}</p>
                        <p class="text-xs text-neutral-500 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                    </div>
                    @if(!$notif->isRead())
                        <span class="h-2 w-2 rounded-full bg-red-500 mt-1.5 shrink-0"></span>
                    @endif
                </a>
            @empty
                <div class="rounded-2xl border border-white/10 bg-neutral-950/35 p-12 text-center text-neutral-400">
                    Aucune notification pour le moment.
                </div>
            @endforelse

            <div class="mt-4">{{ $notifications->links() }}</div>
        </div>
    </div>
</x-app-layout>