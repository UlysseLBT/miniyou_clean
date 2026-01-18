@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;

    $displayName = $user->display_name ?? $user->name;
    $initial     = Str::upper(Str::substr($displayName, 0, 1));

    // Avatar URL (adapter à tes colonnes)
    $avatarUrl = null;
    if (!empty($user->avatar_path)) {
        $avatarUrl = Str::startsWith($user->avatar_path, ['http://','https://'])
            ? $user->avatar_path
            : asset('storage/'.$user->avatar_path);
    } elseif (!empty($user->avatar)) {
        $avatarUrl = asset('storage/'.$user->avatar);
    } elseif (!empty($user->profile_photo_path ?? null)) {
        $avatarUrl = Storage::url($user->profile_photo_path);
    }
@endphp

<section class="rounded-3xl border border-white/10 bg-neutral-950/35 backdrop-blur
                shadow-[0_10px_35px_rgba(0,0,0,.35)] p-6 sm:p-7 text-neutral-100">

    {{-- Header + mini profil --}}
    <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-semibold text-white">
                Informations du profil
            </h2>
            <p class="mt-1 text-sm text-neutral-400">
                Modifie ton profil (nom, email, avatar, description).
            </p>
        </div>

        <div class="flex items-center gap-3">
            {{-- ✅ Preview (caché au départ) --}}
            <img id="avatarPreviewTop"
                 alt="Aperçu avatar"
                 class="hidden h-12 w-12 rounded-full object-cover border border-white/15" />

            {{-- Avatar actuel / initiale --}}
            @if($avatarUrl)
                <img id="avatarCurrentTop"
                     src="{{ $avatarUrl }}"
                     alt="Avatar"
                     class="h-12 w-12 rounded-full object-cover border border-white/15">
            @else
                <div id="avatarPlaceholderTop"
                     class="h-12 w-12 rounded-full bg-white/5 border border-white/10
                            flex items-center justify-center font-semibold text-neutral-200">
                    {{ $initial }}
                </div>
            @endif

            <div class="leading-tight">
                <div class="text-sm font-semibold text-white">{{ $displayName }}</div>
                <div class="text-xs text-neutral-400">{{ $user->email }}</div>
            </div>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    {{-- ✅ enctype obligatoire pour upload --}}
    <form method="post"
          action="{{ route('profile.update') }}"
          enctype="multipart/form-data"
          class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- ✅ Avatar upload + preview --}}
        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="flex items-center gap-4">

                    {{-- ✅ Preview (caché au départ) --}}
                    <img id="avatarPreviewCard"
                         alt="Aperçu avatar"
                         class="hidden h-14 w-14 rounded-full object-cover border border-white/15" />

                    {{-- Avatar actuel / initiale --}}
                    @if($avatarUrl)
                        <img id="avatarCurrentCard"
                             src="{{ $avatarUrl }}"
                             alt="Avatar"
                             class="h-14 w-14 rounded-full object-cover border border-white/15">
                    @else
                        <div id="avatarPlaceholderCard"
                             class="h-14 w-14 rounded-full bg-white/5 border border-white/10
                                    flex items-center justify-center text-lg font-semibold text-neutral-200">
                            {{ $initial }}
                        </div>
                    @endif

                    <div>
                        <p class="text-sm font-medium text-white">Avatar</p>
                        <p class="text-xs text-neutral-400 mt-1">
                            JPG / PNG / WEBP — max 4MB
                        </p>
                    </div>
                </div>

                <div class="sm:ml-auto w-full sm:w-auto">
                    {{-- input caché + bouton --}}
                    <input id="avatar"
                           type="file"
                           name="avatar"
                           accept="image/png,image/jpeg,image/webp"
                           class="hidden" />

                    <button type="button"
                            onclick="document.getElementById('avatar').click()"
                            class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium
                                   bg-white/5 border border-red-500/40 text-white
                                   hover:bg-white/10 transition
                                   shadow-[0_0_0_1px_rgba(239,68,68,.20)]">
                        Changer l’avatar
                    </button>

                    {{-- nom du fichier sélectionné (optionnel) --}}
                    <p id="avatar_filename" class="mt-2 text-xs text-neutral-400"></p>

                    @error('avatar')
                        <p class="mt-2 text-sm text-red-300">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Nom --}}
        <div>
            <x-input-label for="name" :value="__('Name')" class="text-neutral-200" />
            <x-text-input
                id="name"
                name="name"
                type="text"
                class="mt-1 block w-full bg-white/5 border-white/10 text-neutral-100
                       placeholder:text-neutral-500 focus:border-red-500/50 focus:ring-red-500/30"
                :value="old('name', $user->name)"
                required autofocus autocomplete="name"
            />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-neutral-200" />
            <x-text-input
                id="email"
                name="email"
                type="email"
                class="mt-1 block w-full bg-white/5 border-white/10 text-neutral-100
                       placeholder:text-neutral-500 focus:border-red-500/50 focus:ring-red-500/30"
                :value="old('email', $user->email)"
                required autocomplete="username"
            />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-sm text-neutral-300">
                        {{ __('Your email address is unverified.') }}
                        <button
                            form="send-verification"
                            class="underline text-sm text-red-300 hover:text-red-200
                                   focus:outline-none focus:ring-2 focus:ring-red-500/30 rounded-md"
                        >
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-emerald-300">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- ✅ Description (bio) --}}
        <div>
            <x-input-label for="bio" :value="__('Description')" class="text-neutral-200" />
            <textarea
                id="bio"
                name="bio"
                rows="4"
                class="mt-1 block w-full rounded-lg bg-white/5 border border-white/10 text-neutral-100
                       placeholder:text-neutral-500 focus:border-red-500/50 focus:ring-red-500/30"
                placeholder="Ex: Développeur web, fan de jeux, j’écris sur..."
            >{{ old('bio', $user->bio ?? '') }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
            <p class="mt-2 text-xs text-neutral-500">Cette description apparaît sur ta page profil.</p>
        </div>

        {{-- ✅ Liens sociaux --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <x-input-label for="website" :value="__('Website')" class="text-neutral-200" />
                <x-text-input
                    id="website"
                    name="website"
                    type="url"
                    class="mt-1 block w-full bg-white/5 border-white/10 text-neutral-100
                           placeholder:text-neutral-500 focus:border-red-500/50 focus:ring-red-500/30"
                    :value="old('website', $user->website ?? '')"
                    placeholder="https://..."
                />
                <x-input-error class="mt-2" :messages="$errors->get('website')" />
            </div>

            <div>
                <x-input-label for="twitter" :value="__('Twitter / X')" class="text-neutral-200" />
                <x-text-input
                    id="twitter"
                    name="twitter"
                    type="text"
                    class="mt-1 block w-full bg-white/5 border-white/10 text-neutral-100
                           placeholder:text-neutral-500 focus:border-red-500/50 focus:ring-red-500/30"
                    :value="old('twitter', $user->twitter ?? '')"
                    placeholder="@pseudo ou lien"
                />
                <x-input-error class="mt-2" :messages="$errors->get('twitter')" />
            </div>

            <div>
                <x-input-label for="instagram" :value="__('Instagram')" class="text-neutral-200" />
                <x-text-input
                    id="instagram"
                    name="instagram"
                    type="text"
                    class="mt-1 block w-full bg-white/5 border-white/10 text-neutral-100
                           placeholder:text-neutral-500 focus:border-red-500/50 focus:ring-red-500/30"
                    :value="old('instagram', $user->instagram ?? '')"
                    placeholder="@pseudo ou lien"
                />
                <x-input-error class="mt-2" :messages="$errors->get('instagram')" />
            </div>
        </div>

        {{-- Boutons --}}
        <div class="flex items-center gap-4">
            <x-primary-button class="bg-white/5 border border-red-500/40 text-white
                                     hover:bg-white/10 shadow-[0_0_0_1px_rgba(239,68,68,.20)]">
                {{ __('Save') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-neutral-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('avatar');
    const label = document.getElementById('avatar_filename');

    const previewTop  = document.getElementById('avatarPreviewTop');
    const currentTop  = document.getElementById('avatarCurrentTop');
    const placeTop    = document.getElementById('avatarPlaceholderTop');

    const previewCard = document.getElementById('avatarPreviewCard');
    const currentCard = document.getElementById('avatarCurrentCard');
    const placeCard   = document.getElementById('avatarPlaceholderCard');

    let lastObjectUrl = null;

    function showPreview(url) {
      // Top
      if (previewTop) {
        previewTop.src = url;
        previewTop.classList.remove('hidden');
      }
      if (currentTop) currentTop.classList.add('hidden');
      if (placeTop)   placeTop.classList.add('hidden');

      // Card
      if (previewCard) {
        previewCard.src = url;
        previewCard.classList.remove('hidden');
      }
      if (currentCard) currentCard.classList.add('hidden');
      if (placeCard)   placeCard.classList.add('hidden');
    }

    function clearPreview() {
      if (previewTop)  { previewTop.src = '';  previewTop.classList.add('hidden'); }
      if (previewCard) { previewCard.src = ''; previewCard.classList.add('hidden'); }

      if (currentTop) currentTop.classList.remove('hidden');
      if (placeTop)   placeTop.classList.remove('hidden');

      if (currentCard) currentCard.classList.remove('hidden');
      if (placeCard)   placeCard.classList.remove('hidden');
    }

    if (input) {
      input.addEventListener('change', () => {
        const file = input.files && input.files[0];

        // nom de fichier
        if (label) label.textContent = file ? file.name : '';

        // reset url précédent
        if (lastObjectUrl) {
          URL.revokeObjectURL(lastObjectUrl);
          lastObjectUrl = null;
        }

        if (!file) {
          clearPreview();
          return;
        }

        // aperçu instantané
        lastObjectUrl = URL.createObjectURL(file);
        showPreview(lastObjectUrl);
      });
    }
  });
</script>
