<nav x-data="{ open: false }" class="sticky top-0 z-40 apple-chrome border-b border-black/[0.06]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-14">
            <div class="flex items-center gap-6">
                <a href="{{ route('dashboard') }}" class="apple-press inline-flex items-center gap-2.5 rounded-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3]">
                    <x-application-logo class="h-8 w-8" />
                    <span class="text-sm font-semibold tracking-[-0.015em] text-[#1D1D1F]">
                        Inventario
                    </span>
                </a>

                <div class="hidden sm:flex items-center gap-1">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Panel') }}
                    </x-nav-link>

                    @can('manage-users')
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            {{ __('Usuarios y roles') }}
                        </x-nav-link>
                    @endcan
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button type="button" class="apple-press inline-flex items-center gap-2 px-2.5 py-1.5 rounded-xl text-sm font-medium text-[#1D1D1F] hover:bg-[#1D1D1F]/[0.05] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3]">
                            <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-[#1D1D1F] text-[11px] font-semibold text-white">
                                {{ mb_strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <span class="tracking-[-0.01em]">{{ Auth::user()->name }}</span>

                            @if (Auth::user()->role)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium border {{ Auth::user()->role->badgeClasses() }}">
                                    {{ Auth::user()->role->label() }}
                                </span>
                            @endif

                            <svg class="h-3.5 w-3.5 text-[#6E6E73]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-3 py-2 border-b border-black/[0.06] mb-1">
                            <p class="text-xs font-medium text-[#1D1D1F] truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-[#6E6E73] truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Mi perfil') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    class="text-rose-600 hover:bg-rose-50 focus:bg-rose-50"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Cerrar sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-1 flex items-center sm:hidden">
                <button type="button" @click="open = ! open" aria-label="Menú" class="apple-press inline-flex items-center justify-center p-2 rounded-xl text-[#6E6E73] hover:text-[#1D1D1F] hover:bg-[#1D1D1F]/[0.05] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3]">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 7h16M4 12h16M4 17h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div
        x-show="open"
        x-transition:enter="transition-[opacity,transform] ease-sheet duration-200"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition-[opacity,transform] ease-apple duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        class="sm:hidden border-t border-black/[0.06] bg-white/95 backdrop-blur-xl"
        style="display: none;"
    >
        <div class="px-3 pt-3 pb-2 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Panel') }}
            </x-responsive-nav-link>

            @can('manage-users')
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    {{ __('Usuarios y roles') }}
                </x-responsive-nav-link>
            @endcan
        </div>

        <div class="pt-3 pb-3 px-3 border-t border-black/[0.06]">
            <div class="px-3 pb-2 flex items-center justify-between gap-2">
                <div class="min-w-0">
                    <div class="font-medium text-sm text-[#1D1D1F] truncate">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-[#6E6E73] truncate">{{ Auth::user()->email }}</div>
                </div>
                @if (Auth::user()->role)
                    <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium border {{ Auth::user()->role->badgeClasses() }}">
                        {{ Auth::user()->role->label() }}
                    </span>
                @endif
            </div>

            <div class="space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                    {{ __('Mi perfil') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Cerrar sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
