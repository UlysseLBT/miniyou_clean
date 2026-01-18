{{-- resources/views/layouts/navigation.blade.php --}}
<nav x-data="{ open: false }" class="sticky top-0 z-50 border-b border-white/10 bg-neutral-950/40 backdrop-blur">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('posts.index') }}" aria-label="Aller aux posts" title="Posts">
                        <x-application-logo class="block h-8 w-8 text-neutral-100/90" />
                    </a>
                </div>

                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('posts.index')" :active="request()->routeIs('posts.*')">
                        Posts
                    </x-nav-link>

                    <x-nav-link :href="route('communities.index')" :active="request()->routeIs('communities.*')">
                        Communautés
                    </x-nav-link>

                    <x-nav-link :href="route('profile.index')" :active="request()->routeIs('profile.*')">
                        Mon Profil
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48" contentClasses="py-1 bg-neutral-950/90 border border-white/10 backdrop-blur">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-sm font-medium text-neutral-200 hover:bg-white/10 hover:text-white transition">
                            <div>{{ Auth::user()->name }}</div>

                            <svg class="fill-current h-4 w-4 text-neutral-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <div class="my-1 border-t border-white/10"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-lg text-neutral-300 hover:text-white hover:bg-white/5 focus:outline-none focus:bg-white/5 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-white/10 bg-neutral-950/60 backdrop-blur">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('posts.index')" :active="request()->routeIs('posts.*')">
                Posts
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('communities.index')" :active="request()->routeIs('communities.*')">
                Communautés
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('profile.index')" :active="request()->routeIs('profile.*')">
                Mon Profil
            </x-responsive-nav-link>
        </div>
    </div>
</nav>
