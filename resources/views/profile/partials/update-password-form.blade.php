<section class="rounded-3xl border border-white/10 bg-neutral-950/35 backdrop-blur
                shadow-[0_10px_35px_rgba(0,0,0,.35)] p-6 sm:p-7 text-neutral-100">

    <header>
        <h2 class="text-lg font-semibold text-white">
            Modifier le mot de passe
        </h2>
        <p class="mt-1 text-sm text-neutral-400">
            Utilise un mot de passe long et aléatoire pour sécuriser ton compte.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password"
                           value="Mot de passe actuel" class="text-neutral-200" />
            <x-text-input id="update_password_current_password"
                          name="current_password" type="password"
                          class="mt-1 block w-full bg-white/5 border-white/10 text-neutral-100
                                 focus:border-red-500/50 focus:ring-red-500/30"
                          autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password"
                           value="Nouveau mot de passe" class="text-neutral-200" />
            <x-text-input id="update_password_password"
                          name="password" type="password"
                          class="mt-1 block w-full bg-white/5 border-white/10 text-neutral-100
                                 focus:border-red-500/50 focus:ring-red-500/30"
                          autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation"
                           value="Confirmer le mot de passe" class="text-neutral-200" />
            <x-text-input id="update_password_password_confirmation"
                          name="password_confirmation" type="password"
                          class="mt-1 block w-full bg-white/5 border-white/10 text-neutral-100
                                 focus:border-red-500/50 focus:ring-red-500/30"
                          autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="bg-white/5 border border-red-500/40 text-white
                                     hover:bg-white/10 shadow-[0_0_0_1px_rgba(239,68,68,.20)]">
                Sauvegarder
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-neutral-400">
                    Sauvegardé.
                </p>
            @endif
        </div>
    </form>
</section>