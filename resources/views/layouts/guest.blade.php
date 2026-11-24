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
        <div class="min-h-screen flex flex-col justify-center items-center px-4 py-10 relative">
            <div class="absolute top-4 right-4">
                <button
                    type="button"
                    x-data
                    @click="$store.theme.toggle()"
                    :aria-pressed="$store.theme.dark.toString()"
                    aria-label="Cambiar entre modo claro y oscuro"
                    class="apple-press inline-flex items-center gap-1.5 min-h-[44px] sm:min-h-[36px] sm:h-9 px-2.5 rounded-xl text-xs font-medium text-[#6E6E73] dark:text-[#98989D] hover:text-[#1D1D1F] dark:hover:text-[#F5F5F7] hover:bg-black/[0.05] dark:hover:bg-white/[0.08] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] dark:focus-visible:ring-[#0A84FF]"
                >
                    <svg x-show="!$store.theme.dark" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                    </svg>
                    <svg x-show="$store.theme.dark" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                    </svg>
                    <span x-text="$store.theme.dark ? 'Claro' : 'Oscuro'">Oscuro</span>
                </button>
            </div>

            <a href="{{ url('/') }}" class="apple-press inline-flex items-center gap-2.5 mb-7 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] dark:focus-visible:ring-[#0A84FF] rounded-xl px-2 py-1">
                <x-application-logo class="w-9 h-9" />
                <span class="text-lg font-semibold tracking-[-0.02em] text-[#1D1D1F] dark:text-[#F5F5F7]">
                    {{ config('app.name', 'Gestor de Inventario') }}
                </span>
            </a>

            <div class="w-full sm:max-w-[420px] bg-white dark:bg-[#1C1C1E] rounded-2xl border border-black/[0.08] dark:border-white/[0.1] shadow-[0_12px_36px_-16px_rgba(0,0,0,0.12)] dark:shadow-[0_18px_40px_-16px_rgba(0,0,0,0.55)] px-6 py-7 sm:px-8 sm:py-8">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
