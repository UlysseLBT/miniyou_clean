{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MiniYou') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-neutral-100">
    <div class="min-h-screen relative overflow-hidden bg-[#050506]">

        {{-- Glows (mêmes couleurs que accueil/login/register) --}}
        <div aria-hidden="true" class="pointer-events-none absolute inset-0">
            <div class="absolute -top-44 -left-44 h-[520px] w-[520px] rounded-full bg-red-900/35 blur-[130px]"></div>
            <div class="absolute -top-44 -right-44 h-[520px] w-[520px] rounded-full bg-amber-900/18 blur-[130px]"></div>
            <div class="absolute -bottom-64 left-1/3 h-[640px] w-[640px] rounded-full bg-indigo-900/22 blur-[150px]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,rgba(255,255,255,0.08),transparent_55%)]"></div>
        </div>

        {{-- Navigation --}}
        @include('layouts.navigation')

        {{-- Header (optionnel) --}}
        @isset($header)
            <header class="relative border-b border-white/10 bg-neutral-950/30 backdrop-blur">
                <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        {{-- Contenu --}}
        <main class="relative">
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
