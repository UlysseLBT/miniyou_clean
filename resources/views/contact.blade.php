{{-- resources/views/contact.blade.php --}}
@extends('layouts.app')

@section('header')
    <h1 class="text-2xl font-semibold">Contact</h1>
@endsection

@section('content')
<div class="max-w-xl mx-auto p-4">
    {{-- Message de succès --}}
    @if (session('status'))
        <div class="mb-4 p-3 rounded-md border border-green-600 text-green-700 bg-green-50">
            {{ session('status') }}
        </div>
    @endif

    {{-- Erreurs globales --}}
    @if ($errors->any())
        <div class="mb-4 p-3 rounded-md border border-red-600 text-red-700 bg-red-50">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="contact-form" method="POST" action="/contact" class="space-y-4 bg-white rounded-xl shadow p-5">
        @csrf

        <div>
            <label for="name" class="block mb-1 font-medium">Nom</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}"
                   class="w-full border rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                   required autofocus>
            @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="block mb-1 font-medium">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}"
                   class="w-full border rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                   required>
            @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="subject" class="block mb-1 font-medium">Sujet</label>
            <input id="subject" name="subject" type="text" value="{{ old('subject') }}"
                   class="w-full border rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                   required>
            @error('subject') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="message" class="block mb-1 font-medium">Message</label>
            <textarea id="message" name="message" rows="6"
                      class="w-full border rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                      required>{{ old('message') }}</textarea>
            @error('message') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Honeypot anti-bot (caché aux humains) --}}
        <div style="position:absolute; left:-10000px;" aria-hidden="true">
            <label for="website">Votre site web (ne pas remplir)</label>
            <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
        </div>

        {{-- Bouton d’envoi très visible --}}
        <div class="pt-2">
            <button type="submit"
            class="w-full px-5 py-3 rounded-lg bg-emerald-600 text-white font-semibold hover:bg-emerald-700 active:bg-emerald-800">
            Envoyer le message
        </button>
    </div>
</form>
</div>

{{-- Anti double soumission (désactive le bouton après clic) --}}
<script>
document.getElementById('contact-form')?.addEventListener('submit', function(e) {
    const btn = this.querySelector('button[type="submit"]');
    if (btn) {
        btn.disabled = true;
        btn.textContent = 'Envoi...';
    }
});
</script>
@endsection
