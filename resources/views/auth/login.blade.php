<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                          :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Mot de passe')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password"
                          name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                       class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700
                              text-indigo-600 shadow-sm focus:ring-indigo-500"
                       name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Se souvenir de moi</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400
                          hover:text-gray-900 dark:hover:text-gray-100 rounded-md
                          focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                   href="{{ route('password.request') }}">
                    Mot de passe oublié ?
                </a>
            @endif

            <x-primary-button class="ms-3">Se connecter</x-primary-button>
        </div>

        {{-- Lien vers inscription --}}
        @if (Route::has('register'))
            <div class="mt-6 text-center border-t border-gray-200 dark:border-white/10 pt-5">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Pas encore de compte ?
                    <a href="{{ route('register') }}"
                       class="font-medium text-red-500 hover:text-red-400 hover:underline transition">
                        Créer un compte
                    </a>
                </p>
            </div>
        @endif
    </form>
</x-guest-layout>