<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-xl font-semibold tracking-[-0.02em] text-[#1D1D1F] dark:text-[#F5F5F7]">
            Iniciar sesión
        </h1>
        <p class="mt-1 text-sm text-[#6E6E73] dark:text-[#98989D]">
            Ingresa tus credenciales para acceder al inventario.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Correo electrónico')" />
            <x-text-input id="email" class="block mt-1.5 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nombre@correo.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
        </div>

        <div>
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Contraseña')" />
                @if (Route::has('password.request'))
                    <a class="text-xs font-medium text-[#0071E3] dark:text-[#0A84FF] hover:underline rounded focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] dark:focus-visible:ring-[#0A84FF]" href="{{ route('password.request') }}">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>

            <x-text-input id="password" class="block mt-1.5 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
        </div>

        <div class="pt-0.5">
            <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer select-none">
                <input id="remember_me" type="checkbox" class="rounded-md border-black/20 dark:border-white/20 dark:bg-[#2C2C2E] text-[#0071E3] dark:text-[#0A84FF] focus:ring-[#0071E3]/25 dark:focus:ring-[#0A84FF]/30" name="remember">
                <span class="text-sm text-[#6E6E73] dark:text-[#98989D]">Mantener sesión iniciada</span>
            </label>
        </div>

        <div class="pt-2 space-y-3">
            <x-primary-button class="w-full">
                Iniciar sesión
            </x-primary-button>

            @if (Route::has('register'))
                <p class="text-center text-xs text-[#6E6E73] dark:text-[#98989D]">
                    ¿No tienes cuenta?
                    <a href="{{ route('register') }}" class="font-medium text-[#0071E3] dark:text-[#0A84FF] hover:underline">
                        Crear cuenta
                    </a>
                </p>
            @endif
        </div>
    </form>
</x-guest-layout>
