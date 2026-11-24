<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Gestor de Inventario') }}</title>

        <script>
            (() => {
                try {
                    const saved = localStorage.getItem('inventario-theme');
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    const isDark = saved === 'dark' || (saved !== 'light' && prefersDark);
                    document.documentElement.classList.toggle('dark', isDark);
                } catch {}
            })();
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#F5F5F7] text-[#1D1D1F] dark:bg-[#111113] dark:text-[#F5F5F7] transition-colors duration-150">
        <div x-data="{ mobileNavOpen: false }" @keydown.escape.window="mobileNavOpen = false" class="min-h-screen flex flex-col md:flex-row">
            @include('layouts.navigation')

            <div class="flex-1 min-w-0 flex flex-col">
                <div class="sticky top-0 z-30 apple-chrome border-b border-black/[0.06] dark:border-white/[0.08]">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 flex-1 min-w-0 max-w-md">
                            <button
                                type="button"
                                @click="mobileNavOpen = ! mobileNavOpen"
                                :aria-expanded="mobileNavOpen.toString()"
                                aria-label="Menú principal"
                                class="apple-press md:hidden inline-flex items-center justify-center min-h-[44px] min-w-[44px] -ms-2 rounded-xl text-[#6E6E73] dark:text-[#98989D] hover:text-[#1D1D1F] dark:hover:text-[#F5F5F7] hover:bg-[#1D1D1F]/[0.05] dark:hover:bg-white/[0.07] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] dark:focus-visible:ring-[#0A84FF]"
                            >
                                <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                    <path :class="{'hidden': mobileNavOpen, 'inline-flex': ! mobileNavOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 7h16M4 12h16M4 17h16" />
                                    <path :class="{'hidden': ! mobileNavOpen, 'inline-flex': mobileNavOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>

                            <form method="GET" action="{{ route('products.index') }}" role="search" class="relative flex-1 min-w-0">
                                <label for="global-product-search" class="sr-only">Buscar producto o SKU</label>
                                <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-[#6E6E73] dark:text-[#98989D]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                                    <circle cx="11" cy="11" r="7" />
                                    <path stroke-linecap="round" d="m20 20-3.5-3.5" />
                                </svg>
                                <input
                                    id="global-product-search"
                                    type="search"
                                    name="search"
                                    value="{{ request()->routeIs('products.index') ? request('search') : '' }}"
                                    placeholder="Buscar producto o SKU..."
                                    class="w-full h-9 pl-9 pr-3 rounded-xl border border-black/10 dark:border-white/10 bg-white/80 dark:bg-[#2C2C2E] text-sm text-[#1D1D1F] dark:text-[#F5F5F7] placeholder:text-[#86868B] dark:placeholder:text-[#98989D] transition-[border-color,box-shadow,background-color] duration-150 focus:bg-white dark:focus:bg-[#2C2C2E] focus:border-[#0071E3] dark:focus:border-[#0A84FF] focus:outline-none focus:ring-2 focus:ring-[#0071E3]/20 dark:focus:ring-[#0A84FF]/25"
                                >
                            </form>
                        </div>

                        <div class="flex items-center gap-1.5 shrink-0">
                            <div class="relative" x-data="{ open: false }" @click.outside="open = false" @keydown.escape.stop="open = false">
                                <button
                                    type="button"
                                    @click="open = ! open"
                                    :aria-expanded="open.toString()"
                                    aria-label="Alertas de stock bajo"
                                    class="apple-press relative inline-flex items-center justify-center min-h-[44px] min-w-[44px] sm:min-h-[36px] sm:min-w-[36px] sm:h-9 sm:w-9 rounded-xl text-[#6E6E73] dark:text-[#98989D] hover:text-[#1D1D1F] dark:hover:text-[#F5F5F7] hover:bg-[#1D1D1F]/[0.05] dark:hover:bg-white/[0.08] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] dark:focus-visible:ring-[#0A84FF]"
                                >
                                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                                    </svg>
                                    @if (($alertLowStockCount ?? 0) > 0)
                                        <span class="absolute top-1.5 right-1.5 sm:top-1 sm:right-1 inline-flex items-center justify-center min-w-[16px] h-4 px-1 rounded-full bg-amber-500 text-[10px] font-semibold leading-none text-white tabular-nums">
                                            {{ $alertLowStockCount > 99 ? '99+' : $alertLowStockCount }}
                                        </span>
                                    @endif
                                </button>

                                <div
                                    x-show="open"
                                    x-transition:enter="transition-[transform,opacity] ease-apple duration-150"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition-[transform,opacity] ease-apple duration-100"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    class="apple-motion-fade origin-top-right absolute right-0 z-50 mt-2 w-80 max-w-[calc(100vw-2rem)] rounded-2xl bg-white dark:bg-[#2C2C2E] ring-1 ring-black/[0.08] dark:ring-white/[0.12] shadow-[0_18px_36px_-10px_rgba(0,0,0,0.22)]"
                                    style="display: none;"
                                >
                                    <div class="px-4 py-3 border-b border-black/[0.06] dark:border-white/[0.08] flex items-center justify-between gap-2">
                                        <span class="text-xs font-semibold text-[#1D1D1F] dark:text-[#F5F5F7]">Alertas de stock bajo</span>
                                        <span class="text-xs font-medium text-[#6E6E73] dark:text-[#98989D] tabular-nums">
                                            {{ $alertLowStockCount ?? 0 }} {{ ($alertLowStockCount ?? 0) === 1 ? 'producto' : 'productos' }}
                                        </span>
                                    </div>

                                    @if (($alertLowStockProducts ?? collect())->isEmpty())
                                        <div class="px-4 py-6 text-center">
                                            <p class="text-sm font-medium text-[#1D1D1F] dark:text-[#F5F5F7]">Todo en orden</p>
                                            <p class="mt-1 text-xs text-[#6E6E73] dark:text-[#98989D]">No hay productos en o por debajo de su mínimo.</p>
                                        </div>
                                    @else
                                        <ul class="divide-y divide-black/[0.06] dark:divide-white/[0.08] max-h-72 overflow-y-auto">
                                            @foreach ($alertLowStockProducts as $alertProduct)
                                                <li class="px-4 py-2.5 flex items-center justify-between gap-3 hover:bg-[#1D1D1F]/[0.03] dark:hover:bg-white/[0.04]">
                                                    <div class="min-w-0">
                                                        <a href="{{ route('products.show', $alertProduct) }}" @click="open = false" class="block text-sm font-medium text-[#1D1D1F] dark:text-[#F5F5F7] hover:text-[#0071E3] dark:hover:text-[#0A84FF] truncate">
                                                            {{ $alertProduct->name }}
                                                        </a>
                                                        <p class="text-xs text-[#6E6E73] dark:text-[#98989D] tabular-nums">
                                                            {{ $alertProduct->sku }} · {{ $alertProduct->stock }}/{{ $alertProduct->min_stock }} u.
                                                        </p>
                                                    </div>
                                                    <a href="{{ route('movements.create', ['product_id' => $alertProduct->id]) }}" @click="open = false" class="shrink-0 text-xs font-medium text-[#0071E3] dark:text-[#0A84FF] hover:underline">
                                                        Reponer
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif

                                    <div class="px-4 py-2.5 border-t border-black/[0.06] dark:border-white/[0.08]">
                                        <a href="{{ route('products.index', ['stock_status' => 'low']) }}" @click="open = false" class="block text-center text-xs font-medium text-[#0071E3] dark:text-[#0A84FF] hover:underline">
                                            Ver productos con stock bajo
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <button
                                type="button"
                                x-data
                                @click="$store.theme.toggle()"
                                :aria-pressed="$store.theme.dark.toString()"
                                aria-label="Cambiar entre modo claro y oscuro"
                                class="apple-press inline-flex items-center gap-1.5 min-h-[44px] sm:min-h-[36px] sm:h-9 px-2.5 rounded-xl text-xs font-medium text-[#6E6E73] dark:text-[#98989D] hover:text-[#1D1D1F] dark:hover:text-[#F5F5F7] hover:bg-[#1D1D1F]/[0.05] dark:hover:bg-white/[0.08] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] dark:focus-visible:ring-[#0A84FF]"
                            >
                                <svg x-show="!$store.theme.dark" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                                </svg>
                                <svg x-show="$store.theme.dark" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                                </svg>
                                <span class="hidden sm:inline" x-text="$store.theme.dark ? 'Claro' : 'Oscuro'">Oscuro</span>
                            </button>
                        </div>
                    </div>
                </div>

                @isset($header)
                    <header class="pt-7 pb-2">
                        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main class="flex-1 pb-12">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
