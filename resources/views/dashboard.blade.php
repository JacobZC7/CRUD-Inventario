<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-[-0.025em] text-[#1D1D1F]">
                    Hola, {{ auth()->user()->name }}
                </h1>
                <p class="mt-1 text-sm text-[#6E6E73]">
                    Resumen de acceso y estado actual del Gestor de Inventario.
                </p>
            </div>

            @if (auth()->user()->role)
                <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-[#6E6E73]">Rol activo</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border {{ auth()->user()->role->badgeClasses() }}">
                        {{ auth()->user()->role->label() }}
                    </span>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                <section class="lg:col-span-2 apple-surface p-6">
                    <h2 class="text-base font-semibold tracking-[-0.015em] text-[#1D1D1F]">
                        Permisos de tu cuenta
                    </h2>
                    <p class="mt-1 text-sm text-[#6E6E73]">
                        {{ auth()->user()->role?->description() }}
                    </p>

                    <ul class="mt-5 divide-y divide-black/[0.06] text-sm">
                        <li class="py-3 flex items-center justify-between gap-4">
                            <span class="text-[#1D1D1F]">Consultar catálogo y existencias</span>
                            @can('view-inventory')
                                <span class="text-xs font-medium text-emerald-700 bg-emerald-50 border border-emerald-600/20 px-2.5 py-0.5 rounded-full">Habilitado</span>
                            @else
                                <span class="text-xs font-medium text-[#6E6E73] bg-[#F5F5F7] border border-black/10 px-2.5 py-0.5 rounded-full">Restringido</span>
                            @endcan
                        </li>

                        <li class="py-3 flex items-center justify-between gap-4">
                            <span class="text-[#1D1D1F]">Registrar movimientos de stock y ventas</span>
                            @can('manage-inventory')
                                <span class="text-xs font-medium text-emerald-700 bg-emerald-50 border border-emerald-600/20 px-2.5 py-0.5 rounded-full">Habilitado</span>
                            @else
                                <span class="text-xs font-medium text-[#6E6E73] bg-[#F5F5F7] border border-black/10 px-2.5 py-0.5 rounded-full">Restringido</span>
                            @endcan
                        </li>

                        <li class="py-3 flex items-center justify-between gap-4">
                            <span class="text-[#1D1D1F]">Administrar productos y categorías</span>
                            @can('manage-products')
                                <span class="text-xs font-medium text-emerald-700 bg-emerald-50 border border-emerald-600/20 px-2.5 py-0.5 rounded-full">Habilitado</span>
                            @else
                                <span class="text-xs font-medium text-[#6E6E73] bg-[#F5F5F7] border border-black/10 px-2.5 py-0.5 rounded-full">Solo admin</span>
                            @endcan
                        </li>

                        <li class="pt-3 flex items-center justify-between gap-4">
                            <span class="text-[#1D1D1F]">Gestionar usuarios y asignar roles</span>
                            @can('manage-users')
                                <a href="{{ route('admin.users.index') }}" class="apple-press inline-flex items-center gap-1.5 text-xs font-medium text-[#0071E3] hover:underline">
                                    Abrir directorio
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @else
                                <span class="text-xs font-medium text-[#6E6E73] bg-[#F5F5F7] border border-black/10 px-2.5 py-0.5 rounded-full">Solo admin</span>
                            @endcan
                        </li>
                    </ul>
                </section>

                <aside class="apple-surface p-6 flex flex-col justify-between gap-6">
                    <div>
                        <h2 class="text-base font-semibold tracking-[-0.015em] text-[#1D1D1F]">
                            Equipo registrado
                        </h2>
                        <p class="mt-1 text-sm text-[#6E6E73]">
                            Distribución real de cuentas activas en la base de datos.
                        </p>

                        <dl class="mt-5 space-y-3 tabular-nums">
                            <div class="flex items-center justify-between py-2 border-b border-black/[0.06]">
                                <dt class="text-sm text-[#6E6E73]">Total de usuarios</dt>
                                <dd class="text-sm font-semibold text-[#1D1D1F]">{{ $totalUsers }}</dd>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-black/[0.06]">
                                <dt class="text-sm text-[#6E6E73]">Administradores</dt>
                                <dd class="text-sm font-semibold text-[#1D1D1F]">{{ $adminCount }}</dd>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <dt class="text-sm text-[#6E6E73]">Vendedores</dt>
                                <dd class="text-sm font-semibold text-[#1D1D1F]">{{ $vendedorCount }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="pt-4 border-t border-black/[0.06] flex items-center justify-between gap-3">
                        <a href="{{ route('profile.edit') }}" class="text-sm font-medium text-[#0071E3] hover:underline">
                            Editar mi perfil
                        </a>
                        @can('manage-users')
                            <a href="{{ route('admin.users.index') }}" class="apple-press inline-flex items-center px-3.5 py-1.5 rounded-xl bg-[#0071E3] hover:bg-[#0077ED] text-xs font-medium text-white">
                                Gestionar roles
                            </a>
                        @endcan
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
