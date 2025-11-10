@props(['disabled' => false, 'rows' => 4])

<textarea
    {{ $disabled ? 'disabled' : '' }}
    {{-- Merge les classes par défaut avec celles passées à l'appel du composant --}}
    {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!}
    rows="{{ $rows }}"
>{{ $slot }}</textarea>