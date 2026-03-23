<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Profil
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Informations du profil --}}
            <div>
                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- Mot de passe --}}
            <div>
                @include('profile.partials.update-password-form')
            </div>

            {{-- Suppression du compte --}}
            <div>
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>
</x-app-layout>