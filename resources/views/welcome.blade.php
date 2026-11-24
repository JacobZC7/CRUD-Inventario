<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

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
    <body class="font-sans antialiased bg-[#F5F5F7] text-[#1D1D1F] dark:bg-[#111113] dark:text-[#F5F5F7] min-h-screen flex flex-col transition-colors duration-150">
        <header class="sticky top-0 z-40 apple-chrome border-b border-black/[0.06] dark:border-white/[0.08]">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">
                <a href="{{ url('/') }}" class="apple-press inline-flex items-center gap-2.5 rounded-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] dark:focus-visible:ring-[#0A84FF]">
                    <x-application-logo class="h-8 w-8" />
                    <span class="text-sm font-semibold tracking-[-0.015em] text-[#1D1D1F] dark:text-[#F5F5F7]">
                        Gestor de Inventario
                    </span>
                </a>

                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        x-data
                        @click="$store.theme.toggle()"
                        :aria-pressed="$store.theme.dark.toString()"
                        aria-label="Cambiar entre modo claro y oscuro"
                        class="apple-press inline-flex items-center justify-center min-h-[40px] min-w-[40px] px-2.5 rounded-full text-xs font-medium text-[#6E6E73] dark:text-[#98989D] hover:text-[#1D1D1F] dark:hover:text-[#F5F5F7] hover:bg-[#1D1D1F]/[0.05] dark:hover:bg-white/[0.08] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] dark:focus-visible:ring-[#0A84FF]"
                    >
                        <svg x-show="!$store.theme.dark" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                        </svg>
                        <svg x-show="$store.theme.dark" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                        </svg>
                    </button>

                    @if (Route::has('login'))
                        <nav class="flex items-center gap-2">
                            @auth
                                <a href="{{ route('dashboard') }}" class="apple-press inline-flex items-center min-h-[40px] px-4 py-1.5 rounded-full bg-[#0071E3] hover:bg-[#0077ED] dark:bg-[#0A84FF] dark:hover:bg-[#3395FF] text-sm font-medium text-white tracking-[-0.01em] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] dark:focus-visible:ring-[#0A84FF] focus-visible:ring-offset-2 dark:focus-visible:ring-offset-[#111113]">
                                    Ir al panel
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="apple-press inline-flex items-center min-h-[40px] px-3.5 py-1.5 rounded-full text-sm font-medium text-[#1D1D1F] dark:text-[#F5F5F7] hover:bg-[#1D1D1F]/[0.05] dark:hover:bg-white/[0.08] tracking-[-0.01em] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] dark:focus-visible:ring-[#0A84FF]">
                                    Iniciar sesión
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="apple-press inline-flex items-center min-h-[40px] px-4 py-1.5 rounded-full bg-[#0071E3] hover:bg-[#0077ED] dark:bg-[#0A84FF] dark:hover:bg-[#3395FF] text-sm font-medium text-white tracking-[-0.01em] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] dark:focus-visible:ring-[#0A84FF] focus-visible:ring-offset-2 dark:focus-visible:ring-offset-[#111113]">
                                        Crear cuenta
                                    </a>
                                @endif
                            @endauth
                        </nav>
                    @endif
                </div>
            </div>
        </header>

        <main class="flex-1 flex items-center">
            <div class="max-w-5xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
                <div class="apple-hero-enter grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center" data-enter>
                    <div class="lg:col-span-6 space-y-6">
                        <h1 class="text-4xl sm:text-5xl font-semibold tracking-[-0.03em] leading-[1.08] text-[#1D1D1F] dark:text-[#F5F5F7]">
                            Control de inventario, claro y sin fricción.
                        </h1>

                        <p class="text-base sm:text-lg leading-relaxed text-[#6E6E73] dark:text-[#98989D] max-w-[52ch]">
                            Administra catálogo, existencias y movimientos diarios en un solo lugar, con permisos diferenciados para administradores y vendedores.
                        </p>

                        <div class="flex flex-wrap items-center gap-3 pt-1">
                            @auth
                                <a href="{{ route('dashboard') }}" class="apple-press inline-flex items-center justify-center min-h-[44px] gap-2 px-5 py-2.5 rounded-full bg-[#0071E3] hover:bg-[#0077ED] dark:bg-[#0A84FF] dark:hover:bg-[#3395FF] text-sm font-medium text-white tracking-[-0.01em] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] dark:focus-visible:ring-[#0A84FF] focus-visible:ring-offset-2 dark:focus-visible:ring-offset-[#111113]">
                                    Abrir panel
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="apple-press inline-flex items-center justify-center min-h-[44px] gap-2 px-5 py-2.5 rounded-full bg-[#0071E3] hover:bg-[#0077ED] dark:bg-[#0A84FF] dark:hover:bg-[#3395FF] text-sm font-medium text-white tracking-[-0.01em] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] dark:focus-visible:ring-[#0A84FF] focus-visible:ring-offset-2 dark:focus-visible:ring-offset-[#111113]">
                                    Iniciar sesión
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="apple-press inline-flex items-center justify-center min-h-[44px] gap-2 px-5 py-2.5 rounded-full bg-white hover:bg-[#F5F5F7] dark:bg-[#2C2C2E] dark:hover:bg-[#3A3A3C] border border-black/15 dark:border-white/15 text-sm font-medium text-[#1D1D1F] dark:text-[#F5F5F7] tracking-[-0.01em] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] dark:focus-visible:ring-[#0A84FF] focus-visible:ring-offset-2 dark:focus-visible:ring-offset-[#111113]">
                                        Crear cuenta
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>

                    <div class="lg:col-span-6">
                        <div class="apple-surface overflow-hidden divide-y divide-black/[0.06] dark:divide-white/[0.08]">
                            <div class="px-5 py-3.5 bg-[#F5F5F7]/60 dark:bg-white/[0.04] flex items-center justify-between gap-4">
                                <span class="text-xs font-semibold tracking-[-0.01em] text-[#1D1D1F] dark:text-[#F5F5F7]">
                                    Vista previa · MiniMercado
                                </span>
                                <span class="text-xs text-[#6E6E73] dark:text-[#98989D] tabular-nums">
                                    10 productos · 207 u.
                                </span>
                            </div>

                            <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-[#1D1D1F] dark:text-[#F5F5F7] truncate">Leche Entera UHT 1 L</p>
                                    <p class="text-xs font-mono text-[#6E6E73] dark:text-[#98989D]">LAC-001 · Lácteos</p>
                                </div>
                                <div class="shrink-0 flex items-center gap-3 tabular-nums">
                                    <span class="text-sm font-semibold text-[#1D1D1F] dark:text-[#F5F5F7]">30 u.</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border bg-emerald-50 text-emerald-700 border-emerald-600/20 dark:bg-emerald-500/15 dark:text-emerald-300 dark:border-emerald-400/25">
                                        Disponible
                                    </span>
                                </div>
                            </div>

                            <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-[#1D1D1F] dark:text-[#F5F5F7] truncate">Yogurt Griego Natural 500 g</p>
                                    <p class="text-xs font-mono text-[#6E6E73] dark:text-[#98989D]">LAC-002 · Mínimo 6</p>
                                </div>
                                <div class="shrink-0 flex items-center gap-3 tabular-nums">
                                    <span class="text-sm font-semibold text-[#1D1D1F] dark:text-[#F5F5F7]">4 u.</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border bg-amber-50 text-amber-700 border-amber-600/20 dark:bg-amber-500/15 dark:text-amber-300 dark:border-amber-400/25">
                                        Stock bajo
                                    </span>
                                </div>
                            </div>

                            <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-[#1D1D1F] dark:text-[#F5F5F7] truncate">Café Molido Tostado 250 g</p>
                                    <p class="text-xs font-mono text-[#6E6E73] dark:text-[#98989D]">DES-003 · Mínimo 5</p>
                                </div>
                                <div class="shrink-0 flex items-center gap-3 tabular-nums">
                                    <span class="text-sm font-semibold text-[#1D1D1F] dark:text-[#F5F5F7]">0 u.</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border bg-rose-50 text-rose-700 border-rose-600/20 dark:bg-rose-500/15 dark:text-rose-300 dark:border-rose-400/25">
                                        Agotado
                                    </span>
                                </div>
                            </div>

                            <div class="px-5 py-4 bg-[#F5F5F7]/40 dark:bg-white/[0.02] grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="flex items-start gap-2.5">
                                    <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium border bg-[#0071E3]/10 text-[#005BB5] border-[#0071E3]/20 dark:bg-[#0A84FF]/15 dark:text-[#64B5F6] dark:border-[#0A84FF]/30">
                                        admin
                                    </span>
                                    <p class="text-xs text-[#6E6E73] dark:text-[#98989D]">
                                        Catálogo, categorías, usuarios y ajustes.
                                    </p>
                                </div>

                                <div class="flex items-start gap-2.5">
                                    <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium border bg-emerald-50 text-emerald-700 border-emerald-600/20 dark:bg-emerald-500/15 dark:text-emerald-300 dark:border-emerald-400/25">
                                        vendedor
                                    </span>
                                    <p class="text-xs text-[#6E6E73] dark:text-[#98989D]">
                                        Consulta de stock y entradas/salidas.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
