@php
    use Illuminate\Support\Str;

    $displayName = $user->display_name ?? $user->name;
    $initial     = Str::upper(Str::substr($displayName, 0, 1));
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Paramètres du compte
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Formulaire infos du compte + avatar --}}
            <div class="bg-slate-900 text-white sm:rounded-xl shadow-sm p-6 sm:p-7">
                <h3 class="text-lg font-semibold mb-4">
                    Informations du compte
                </h3>

                <form method="POST"
                      action="{{ route('profile.update') }}"
                      enctype="multipart/form-data"
                      class="space-y-5">
                    @csrf
                    @method('patch')

                    {{-- Avatar + nom en haut --}}
                    <div class="flex items-center gap-4">
                        @if(!empty($user->avatar_path))
                            <img src="{{ asset('storage/'.$user->avatar_path) }}"
                                 alt="Avatar"
                                 class="h-14 w-14 rounded-full object-cover border border-slate-600">
                        @else
                            <div class="h-14 w-14 rounded-full bg-emerald-400 flex items-center justify-center text-lg font-semibold">
                                {{ $initial }}
                            </div>
                        @endif

                        <div class="flex-1">
                            <label for="avatar" class="block text-xs font-medium text-slate-200 mb-1">
                                Changer d’avatar
                            </label>
                            <input id="avatar" name="avatar" type="file"
                                   class="block w-full text-xs text-slate-200">
                            @error('avatar')
                                <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Nom --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-100 mb-1">
                            Nom
                        </label>
                        <input id="name" name="name" type="text"
                               class="w-full rounded-lg border border-slate-600 bg-slate-800 text-sm text-white
                                      focus:border-emerald-500 focus:ring-emerald-500"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-100 mb-1">
                            Email
                        </label>
                        <input id="email" name="email" type="email"
                               class="w-full rounded-lg border border-slate-600 bg-slate-800 text-sm text-white
                                      focus:border-emerald-500 focus:ring-emerald-500"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Bio --}}
                    <div>
                        <label for="bio" class="block text-sm font-medium text-slate-100 mb-1">
                            Bio
                        </label>
                        <textarea id="bio" name="bio" rows="4"
                                  class="w-full rounded-lg border border-slate-600 bg-slate-800 text-sm text-white
                                         focus:border-emerald-500 focus:ring-emerald-500">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio')
                            <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Website / Twitter / Instagram si tu les as --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label for="website" class="block text-sm font-medium text-slate-100 mb-1">
                                Site web
                            </label>
                            <input id="website" name="website" type="url"
                                   class="w-full rounded-lg border border-slate-600 bg-slate-800 text-sm text-white
                                          focus:border-emerald-500 focus:ring-emerald-500"
                                   value="{{ old('website', $user->website) }}">
                        </div>
                        <div>
                            <label for="twitter" class="block text-sm font-medium text-slate-100 mb-1">
                                Twitter
                            </label>
                            <input id="twitter" name="twitter" type="text"
                                   class="w-full rounded-lg border border-slate-600 bg-slate-800 text-sm text-white
                                          focus:border-emerald-500 focus:ring-emerald-500"
                                   value="{{ old('twitter', $user->twitter) }}">
                        </div>
                        <div>
                            <label for="instagram" class="block text-sm font-medium text-slate-100 mb-1">
                                Instagram
                            </label>
                            <input id="instagram" name="instagram" type="text"
                                   class="w-full rounded-lg border border-slate-600 bg-slate-800 text-sm text-white
                                          focus:border-emerald-500 focus:ring-emerald-500"
                                   value="{{ old('instagram', $user->instagram) }}">
                        </div>
                    </div>

                    {{-- Bouton Enregistrer en bas à droite --}}
                    <div class="pt-4 flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 rounded-md bg-emerald-500 text-sm font-medium text-white
                                       hover:bg-emerald-600">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>

            {{-- Mot de passe --}}
            <div class="bg-white shadow-sm sm:rounded-xl p-6 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Suppression du compte --}}
            <div class="bg-white shadow-sm sm:rounded-xl p-6 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
