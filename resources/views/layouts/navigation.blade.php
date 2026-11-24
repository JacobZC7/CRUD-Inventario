<aside class="hidden md:block md:w-64 md:shrink-0 apple-sidebar border-r border-black/[0.06] dark:border-white/[0.08]">
    <div class="md:sticky md:top-0 md:h-screen flex flex-col">
        <div class="h-14 px-4 flex items-center border-b border-black/[0.06] dark:border-white/[0.08]">
            <a href="{{ route('dashboard') }}" class="apple-press inline-flex items-center gap-2.5 rounded-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] dark:focus-visible:ring-[#0A84FF]">
                <x-application-logo class="h-8 w-8 shrink-0" />
                <span class="text-sm font-semibold tracking-[-0.015em] text-[#1D1D1F] dark:text-[#F5F5F7]">
                    Inventario
                </span>
            </a>
        </div>

        <nav aria-label="Navegación principal" class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                </svg>
                <span>{{ __('Panel') }}</span>
            </x-nav-link>

            <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                <svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                </svg>
                <span>{{ __('Productos') }}</span>
            </x-nav-link>

            <x-nav-link :href="route('movements.index')" :active="request()->routeIs('movements.*')">
                <svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                </svg>
                <span>{{ __('Movimientos') }}</span>
            </x-nav-link>

            @can('manage-products')
                <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                    <svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                    </svg>
                    <span>{{ __('Categorías') }}</span>
                </x-nav-link>
            @endcan

            @can('manage-users')
                <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    <svg class="h-[18px] w-[18px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                    <span>{{ __('Usuarios y roles') }}</span>
                </x-nav-link>
            @endcan
        </nav>

        <div class="p-3 border-t border-black/[0.06] dark:border-white/[0.08] space-y-2.5">
            <div class="px-2 py-1.5 flex items-start gap-2.5">
                <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#1D1D1F] dark:bg-white/15 text-xs font-semibold text-white">
                    {{ mb_strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                </span>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-[#1D1D1F] dark:text-[#F5F5F7] truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-[#6E6E73] dark:text-[#98989D] truncate">{{ Auth::user()->email }}</p>
                    @if (Auth::user()->role)
                        <span class="mt-1.5 inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium border {{ Auth::user()->role->badgeClasses() }}">
                            {{ Auth::user()->role->label() }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 gap-1">
                <a href="{{ route('profile.edit') }}" class="apple-press inline-flex items-center justify-center px-2.5 py-2 rounded-xl text-xs font-medium {{ request()->routeIs('profile.edit') ? 'bg-white dark:bg-white/[0.1] text-[#1D1D1F] dark:text-[#F5F5F7] shadow-sm' : 'text-[#6E6E73] dark:text-[#98989D] hover:text-[#1D1D1F] dark:hover:text-[#F5F5F7] hover:bg-[#1D1D1F]/[0.05] dark:hover:bg-white/[0.06]' }}">
                    {{ __('Mi perfil') }}
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="apple-press w-full inline-flex items-center justify-center px-2.5 py-2 rounded-xl text-xs font-medium text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10">
                        {{ __('Cerrar sesión') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>

<div
    x-show="mobileNavOpen"
    class="md:hidden fixed inset-0 z-40 flex"
    style="display: none;"
>
    <div
        x-show="mobileNavOpen"
        x-transition:enter="transition-opacity ease-apple duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-apple duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="mobileNavOpen = false"
        class="fixed inset-0 bg-[#1D1D1F]/35 dark:bg-black/60"
    ></div>

    <nav
        x-show="mobileNavOpen"
        x-transition:enter="transition-[transform,opacity] ease-sheet duration-200"
        x-transition:enter-start="-translate-x-full opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave="transition-[transform,opacity] ease-apple duration-150"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="-translate-x-full opacity-0"
        aria-label="Navegación móvil"
        class="apple-motion-fade relative z-10 w-72 max-w-[85vw] h-full flex flex-col bg-white dark:bg-[#1C1C1E] border-r border-black/[0.08] dark:border-white/[0.1] shadow-[0_24px_48px_-12px_rgba(0,0,0,0.28)]"
    >
        <div class="h-14 px-4 flex items-center justify-between border-b border-black/[0.06] dark:border-white/[0.08]">
            <a href="{{ route('dashboard') }}" @click="mobileNavOpen = false" class="inline-flex items-center gap-2.5 rounded-lg">
                <x-application-logo class="h-8 w-8" />
                <span class="text-sm font-semibold tracking-[-0.015em] text-[#1D1D1F] dark:text-[#F5F5F7]">
                    Inventario
                </span>
            </a>

            <button
                type="button"
                @click="mobileNavOpen = false"
                aria-label="Cerrar menú"
                class="apple-press inline-flex items-center justify-center min-h-[44px] min-w-[44px] -me-2 rounded-xl text-[#6E6E73] dark:text-[#98989D] hover:text-[#1D1D1F] dark:hover:text-[#F5F5F7]"
            >
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-3 py-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" @click="mobileNavOpen = false">
                {{ __('Panel') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" @click="mobileNavOpen = false">
                {{ __('Productos') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('movements.index')" :active="request()->routeIs('movements.*')" @click="mobileNavOpen = false">
                {{ __('Movimientos') }}
            </x-responsive-nav-link>

            @can('manage-products')
                <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')" @click="mobileNavOpen = false">
                    {{ __('Categorías') }}
                </x-responsive-nav-link>
            @endcan

            @can('manage-users')
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" @click="mobileNavOpen = false">
                    {{ __('Usuarios y roles') }}
                </x-responsive-nav-link>
            @endcan
        </div>

        <div class="p-3 border-t border-black/[0.06] dark:border-white/[0.08]">
            <div class="px-3 pb-2.5 flex items-center justify-between gap-2">
                <div class="min-w-0">
                    <div class="font-medium text-sm text-[#1D1D1F] dark:text-[#F5F5F7] truncate">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-[#6E6E73] dark:text-[#98989D] truncate">{{ Auth::user()->email }}</div>
                </div>
                @if (Auth::user()->role)
                    <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium border {{ Auth::user()->role->badgeClasses() }}">
                        {{ Auth::user()->role->label() }}
                    </span>
                @endif
            </div>

            <div class="space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')" @click="mobileNavOpen = false">
                    {{ __('Mi perfil') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            class="text-rose-600 dark:text-rose-400"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Cerrar sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </nav>
</div>
