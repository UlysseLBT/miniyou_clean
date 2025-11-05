@props(['active' => false])

@php
    $isActive = (bool) $active;

    $base = 'inline-flex items-center px-3 py-2 text-sm font-medium
             border-2 rounded-none transition-colors duration-150 focus:outline-none
             focus:ring-2 focus:ring-green-500';
    $activeClasses   = 'border-green-600 text-green-700 bg-white';
    $inactiveClasses = 'border-transparent text-gray-700 hover:text-green-700 hover:border-green-400';
@endphp

<a {{ $attributes->merge(['class' => $base.' '.($isActive ? $activeClasses : $inactiveClasses)]) }}
   aria-current="{{ $isActive ? 'page' : 'false' }}">
    {{ $slot }}
</a>
