<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Gestor de Inventario') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#F5F5F7] text-[#1D1D1F] min-h-screen flex flex-col">
        <header class="sticky top-0 z-40 apple-chrome border-b border-black/[0.06]">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">
                <a href="{{ url('/') }}" class="apple-press inline-flex items-center gap-2.5 rounded-lg focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3]">
                    <x-application-logo class="h-8 w-8" />
                    <span class="text-sm font-semibold tracking-[-0.015em] text-[#1D1D1F]">
                        Gestor de Inventario
                    </span>
                </a>

                @if (Route::has('login'))
                    <nav class="flex items-center gap-2">
                        @auth
                            <a href="{{ route('dashboard') }}" class="apple-press inline-flex items-center min-h-[40px] px-4 py-1.5 rounded-full bg-[#0071E3] hover:bg-[#0077ED] text-sm font-medium text-white tracking-[-0.01em] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] focus-visible:ring-offset-2">
                                Ir al panel
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="apple-press inline-flex items-center min-h-[40px] px-3.5 py-1.5 rounded-full text-sm font-medium text-[#1D1D1F] hover:bg-[#1D1D1F]/[0.05] tracking-[-0.01em] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3]">
                                Iniciar sesión
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="apple-press inline-flex items-center min-h-[40px] px-4 py-1.5 rounded-full bg-[#0071E3] hover:bg-[#0077ED] text-sm font-medium text-white tracking-[-0.01em] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] focus-visible:ring-offset-2">
                                    Crear cuenta
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
        </header>

        <main class="flex-1 flex items-center">
            <div class="max-w-5xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
                <div class="apple-hero-enter grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center" data-enter>
                    <div class="lg:col-span-6 space-y-6">
                        <h1 class="text-4xl sm:text-5xl font-semibold tracking-[-0.03em] leading-[1.08] text-[#1D1D1F]">
                            Control de inventario, claro y sin fricción.
                        </h1>

                        <p class="text-base sm:text-lg leading-relaxed text-[#6E6E73] max-w-[52ch]">
                            Administra catálogo, existencias y movimientos diarios en un solo lugar, con permisos diferenciados para administradores y vendedores.
                        </p>

                        <div class="flex flex-wrap items-center gap-3 pt-1">
                            @auth
                                <a href="{{ route('dashboard') }}" class="apple-press inline-flex items-center justify-center min-h-[44px] gap-2 px-5 py-2.5 rounded-full bg-[#0071E3] hover:bg-[#0077ED] text-sm font-medium text-white tracking-[-0.01em] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] focus-visible:ring-offset-2">
                                    Abrir panel
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="apple-press inline-flex items-center justify-center min-h-[44px] gap-2 px-5 py-2.5 rounded-full bg-[#0071E3] hover:bg-[#0077ED] text-sm font-medium text-white tracking-[-0.01em] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] focus-visible:ring-offset-2">
                                    Iniciar sesión
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="apple-press inline-flex items-center justify-center min-h-[44px] gap-2 px-5 py-2.5 rounded-full bg-white hover:bg-[#F5F5F7] border border-black/15 text-sm font-medium text-[#1D1D1F] tracking-[-0.01em] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] focus-visible:ring-offset-2">
                                        Crear cuenta
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>

                    <div class="lg:col-span-6">
                        <div class="apple-surface overflow-hidden divide-y divide-black/[0.06]">
                            <div class="px-5 py-3.5 bg-[#F5F5F7]/60 flex items-center justify-between gap-4">
                                <span class="text-xs font-semibold tracking-[-0.01em] text-[#1D1D1F]">
                                    Vista previa · MiniMercado
                                </span>
                                <span class="text-xs text-[#6E6E73] tabular-nums">
                                    10 productos · 207 u.
                                </span>
                            </div>

                            <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-[#1D1D1F] truncate">Leche Entera UHT 1 L</p>
                                    <p class="text-xs font-mono text-[#6E6E73]">LAC-001 · Lácteos</p>
                                </div>
                                <div class="shrink-0 flex items-center gap-3 tabular-nums">
                                    <span class="text-sm font-semibold text-[#1D1D1F]">30 u.</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border bg-emerald-50 text-emerald-700 border-emerald-600/20">
                                        Disponible
                                    </span>
                                </div>
                            </div>

                            <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-[#1D1D1F] truncate">Yogurt Griego Natural 500 g</p>
                                    <p class="text-xs font-mono text-[#6E6E73]">LAC-002 · Mínimo 6</p>
                                </div>
                                <div class="shrink-0 flex items-center gap-3 tabular-nums">
                                    <span class="text-sm font-semibold text-[#1D1D1F]">4 u.</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border bg-amber-50 text-amber-700 border-amber-600/20">
                                        Stock bajo
                                    </span>
                                </div>
                            </div>

                            <div class="px-5 py-3.5 flex items-center justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-[#1D1D1F] truncate">Café Molido Tostado 250 g</p>
                                    <p class="text-xs font-mono text-[#6E6E73]">DES-003 · Mínimo 5</p>
                                </div>
                                <div class="shrink-0 flex items-center gap-3 tabular-nums">
                                    <span class="text-sm font-semibold text-[#1D1D1F]">0 u.</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border bg-rose-50 text-rose-700 border-rose-600/20">
                                        Agotado
                                    </span>
                                </div>
                            </div>

                            <div class="px-5 py-4 bg-[#F5F5F7]/40 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="flex items-start gap-2.5">
                                    <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium border bg-[#0071E3]/10 text-[#005BB5] border-[#0071E3]/20">
                                        admin
                                    </span>
                                    <p class="text-xs text-[#6E6E73]">
                                        Catálogo, categorías, usuarios y ajustes.
                                    </p>
                                </div>

                                <div class="flex items-start gap-2.5">
                                    <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium border bg-emerald-50 text-emerald-700 border-emerald-600/20">
                                        vendedor
                                    </span>
                                    <p class="text-xs text-[#6E6E73]">
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
