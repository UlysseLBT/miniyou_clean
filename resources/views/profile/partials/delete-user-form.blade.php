<section class="rounded-3xl border border-red-500/20 bg-neutral-950/35 backdrop-blur
                shadow-[0_10px_35px_rgba(0,0,0,.35)] p-6 sm:p-7 text-neutral-100">

    <header>
        <h2 class="text-lg font-semibold text-white">
            Supprimer le compte
        </h2>
        <p class="mt-1 text-sm text-neutral-400">
            Une fois ton compte supprimé, toutes tes données seront définitivement effacées.
            Télécharge tes données avant de procéder.
        </p>
    </header>

    <div class="mt-6">
        <x-danger-button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="rounded-full px-4 py-2 text-sm font-medium">
            Supprimer mon compte
        </x-danger-button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}"
              class="p-6 bg-neutral-900 rounded-2xl">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-white">
                Confirmer la suppression du compte
            </h2>

            <p class="mt-2 text-sm text-neutral-400">
                Cette action est irréversible. Toutes tes données seront supprimées définitivement.
                Saisis ton mot de passe pour confirmer.
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="Mot de passe" class="text-neutral-200 sr-only" />
                <x-text-input id="password" name="password" type="password"
                    class="mt-1 block w-3/4 bg-white/5 border-white/10 text-neutral-100
                           placeholder:text-neutral-500 focus:border-red-500/50 focus:ring-red-500/30"
                    placeholder="Ton mot de passe" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')"
                    class="rounded-full bg-white/5 border border-white/10 text-neutral-300
                           hover:bg-white/10 transition">
                    Annuler
                </x-secondary-button>

                <x-danger-button class="rounded-full">
                    Supprimer définitivement
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>