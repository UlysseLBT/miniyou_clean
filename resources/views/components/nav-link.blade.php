@props(['active' => false])

@php
    $classes = $active
        ? 'inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-red-500/40 bg-white/10 text-white shadow-[0_0_0_1px_rgba(239,68,68,.18)]'
        : 'inline-flex items-center px-3 py-2 text-sm font-medium rounded-lg border border-white/10 bg-white/5 text-neutral-200 hover:bg-white/10 hover:text-white';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
