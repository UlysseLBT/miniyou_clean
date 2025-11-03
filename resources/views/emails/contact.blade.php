@extends('layouts.app')

@section('header')
    <h1 class="text-2xl font-semibold">Contact</h1>
@endsection

@section('content')
<div class="max-w-xl mx-auto p-4">
    <form method="POST" action="{{ route('contact.send') }}" class="space-y-4 bg-white p-4 rounded shadow">
        @csrf
        <input name="name"    class="w-full border p-2" placeholder="Nom">
        <input name="email"   type="email" class="w-full border p-2" placeholder="Email">
        <input name="subject" class="w-full border p-2" placeholder="Sujet">
        <textarea name="message" rows="5" class="w-full border p-2" placeholder="Message"></textarea>
        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Envoyer</button>
    </form>
</div>
@endsection
