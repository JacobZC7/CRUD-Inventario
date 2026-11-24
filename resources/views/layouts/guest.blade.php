<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Gestor de Inventario') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#F5F5F7] text-[#1D1D1F]">
        <div class="min-h-screen flex flex-col justify-center items-center px-4 py-10">
            <a href="{{ url('/') }}" class="apple-press inline-flex items-center gap-2.5 mb-7 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] rounded-xl px-2 py-1">
                <x-application-logo class="w-9 h-9" />
                <span class="text-lg font-semibold tracking-[-0.02em] text-[#1D1D1F]">
                    {{ config('app.name', 'Gestor de Inventario') }}
                </span>
            </a>

            <div class="w-full sm:max-w-[420px] bg-white rounded-2xl border border-black/[0.08] shadow-[0_12px_36px_-16px_rgba(0,0,0,0.12)] px-6 py-7 sm:px-8 sm:py-8">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
