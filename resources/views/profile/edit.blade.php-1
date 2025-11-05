@extends('layouts.app')

@section('header')
  <h1 class="text-2xl font-semibold">Mon profil</h1>
@endsection

@section('content')
<div class="max-w-xl mx-auto p-4 bg-white rounded shadow space-y-6">

    @if (session('status'))
        <div class="p-3 border border-green-600 bg-green-50 text-green-700 rounded">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PATCH')

        {{-- Display name --}}
        <div>
            <label class="block text-sm font-medium">Display name</label>
            <input
                class="mt-1 border rounded p-2 w-full"
                type="text"
                name="display_name"
                value="{{ old('display_name', $user->display_name) }}"
            >
            @error('display_name')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>

        {{-- Bio --}}
        <div>
            <label class="block text-sm font-medium">Bio</label>
            <textarea
                class="mt-1 border rounded p-2 w-full"
                name="bio"
                rows="4"
            >{{ old('bio', $user->bio) }}</textarea>
            @error('bio')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>

        {{-- Website --}}
        <div>
            <label class="block text-sm font-medium">Site web</label>
            <input
                class="mt-1 border rounded p-2 w-full"
                type="url"
                name="website"
                value="{{ old('website', $user->website) }}"
            >
            @error('website')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>

        {{-- Twitter --}}
        <div>
            <label class="block text-sm font-medium">Twitter</label>
            <input
                class="mt-1 border rounded p-2 w-full"
                type="text"
                name="twitter"
                value="{{ old('twitter', $user->twitter) }}"
            >
            @error('twitter')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>

        {{-- Instagram --}}
        <div>
            <label class="block text-sm font-medium">Instagram</label>
            <input
                class="mt-1 border rounded p-2 w-full"
                type="text"
                name="instagram"
                value="{{ old('instagram', $user->instagram) }}"
            >
            @error('instagram')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>

        {{-- Avatar --}}
        <div class="space-y-2">
            <label class="block text-sm font-medium">Avatar</label>
            @if($user->avatar_path)
                <img src="{{ asset('storage/'.$user->avatar_path) }}" alt="Avatar" class="h-24 w-24 rounded-full object-cover">
            @endif
            <input class="block" type="file" name="avatar" accept="image/*">
            @error('avatar')
                <div class="text-red-600 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <button class="px-4 py-2 bg-emerald-600 text-white rounded">
            Enregistrer
        </button>
    </form>
</div>
@endsection
