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
                            <a href="{{ route('dashboard') }}" class="apple-press inline-flex items-center px-4 py-1.5 rounded-full bg-[#0071E3] hover:bg-[#0077ED] text-sm font-medium text-white tracking-[-0.01em] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] focus-visible:ring-offset-2">
                                Ir al panel
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="apple-press inline-flex items-center px-3.5 py-1.5 rounded-full text-sm font-medium text-[#1D1D1F] hover:bg-[#1D1D1F]/[0.05] tracking-[-0.01em] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3]">
                                Iniciar sesión
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="apple-press inline-flex items-center px-4 py-1.5 rounded-full bg-[#0071E3] hover:bg-[#0077ED] text-sm font-medium text-white tracking-[-0.01em] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] focus-visible:ring-offset-2">
                                    Crear cuenta
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
        </header>

        <main class="flex-1 flex items-center">
            <div class="max-w-5xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-14 sm:py-20">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
                    <div class="lg:col-span-7 space-y-6">
                        <h1 class="text-4xl sm:text-5xl font-semibold tracking-[-0.03em] leading-[1.08] text-[#1D1D1F]">
                            Control de inventario, claro y sin fricción.
                        </h1>

                        <p class="text-base sm:text-lg leading-relaxed text-[#6E6E73] max-w-[54ch]">
                            Administra productos, existencias y movimientos diarios con control de acceso por roles para administradores y vendedores.
                        </p>

                        <div class="flex flex-wrap items-center gap-3 pt-1">
                            @auth
                                <a href="{{ route('dashboard') }}" class="apple-press inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#0071E3] hover:bg-[#0077ED] text-sm font-medium text-white tracking-[-0.01em] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] focus-visible:ring-offset-2">
                                    Abrir panel
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="apple-press inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#0071E3] hover:bg-[#0077ED] text-sm font-medium text-white tracking-[-0.01em] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] focus-visible:ring-offset-2">
                                    Iniciar sesión
                                </a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="apple-press inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white hover:bg-[#F5F5F7] border border-black/15 text-sm font-medium text-[#1D1D1F] tracking-[-0.01em] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] focus-visible:ring-offset-2">
                                        Crear cuenta
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>

                    <div class="lg:col-span-5">
                        <div class="apple-surface p-6 divide-y divide-black/[0.06]">
                            <div class="pb-4 flex items-start justify-between gap-4">
                                <div>
                                    <h2 class="text-sm font-semibold tracking-[-0.01em] text-[#1D1D1F]">
                                        Administrador
                                    </h2>
                                    <p class="mt-1 text-sm text-[#6E6E73]">
                                        Gestiona usuarios, roles, catálogo completo de productos y ajustes de inventario.
                                    </p>
                                </div>
                                <span class="shrink-0 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border bg-[#0071E3]/10 text-[#005BB5] border-[#0071E3]/20">
                                    admin
                                </span>
                            </div>

                            <div class="py-4 flex items-start justify-between gap-4">
                                <div>
                                    <h2 class="text-sm font-semibold tracking-[-0.01em] text-[#1D1D1F]">
                                        Vendedor
                                    </h2>
                                    <p class="mt-1 text-sm text-[#6E6E73]">
                                        Consulta existencias en tiempo real y registra salidas o movimientos operativos.
                                    </p>
                                </div>
                                <span class="shrink-0 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border bg-emerald-50 text-emerald-700 border-emerald-600/20">
                                    vendedor
                                </span>
                            </div>

                            <div class="pt-4">
                                <p class="text-xs text-[#6E6E73]">
                                    Autenticación Laravel Breeze · Laravel {{ app()->version() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
