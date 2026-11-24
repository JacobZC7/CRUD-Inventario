<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-[-0.025em] text-[#1D1D1F] dark:text-[#F5F5F7]">
                    Panel de inventario
                </h1>
                <p class="mt-1 text-sm text-[#6E6E73] dark:text-[#98989D]">
                    Hola, {{ auth()->user()->name }}. Estado actual del catálogo, existencias y movimientos.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('movements.create') }}" class="apple-press inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white hover:bg-[#F5F5F7] dark:bg-[#2C2C2E] dark:hover:bg-[#3A3A3C] border border-black/15 dark:border-white/15 text-sm font-medium text-[#1D1D1F] dark:text-[#F5F5F7]">
                    Registrar movimiento
                </a>

                @can('manage-products')
                    <a href="{{ route('products.create') }}" class="apple-press inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#0071E3] hover:bg-[#0077ED] dark:bg-[#0A84FF] dark:hover:bg-[#3395FF] text-sm font-medium text-white">
                        Nuevo producto
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="apple-surface overflow-hidden grid grid-cols-2 lg:grid-cols-4 divide-y lg:divide-y-0 lg:divide-x divide-black/[0.06] dark:divide-white/[0.08] tabular-nums">
                <a href="{{ route('products.index') }}" class="group p-5 transition-colors duration-150 hover:bg-black/[0.025] dark:hover:bg-white/[0.03] focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-[#0071E3] dark:focus-visible:ring-[#0A84FF]">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-xs font-medium text-[#6E6E73] dark:text-[#98989D]">Productos registrados</p>
                        <svg class="h-3.5 w-3.5 text-[#86868B] dark:text-[#6E6E73] transition-transform duration-150 group-hover:translate-x-0.5 group-hover:text-[#0071E3] dark:group-hover:text-[#0A84FF]" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 .02-1.06L11.168 10 7.23 6.29a.75.75 0 1 1 1.04-1.08l4.5 4.25a.75.75 0 0 1 0 1.08l-4.5 4.25a.75.75 0 0 1-1.06-.02Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="mt-1.5 text-2xl font-semibold tracking-[-0.02em] text-[#1D1D1F] dark:text-[#F5F5F7]">{{ $totalProducts }}</p>
                    <p class="mt-1 text-xs text-[#6E6E73] dark:text-[#98989D]">{{ $activeProducts }} activos en catálogo</p>
                </a>

                <a href="{{ route('products.index', ['stock_status' => 'ok']) }}" class="group p-5 transition-colors duration-150 hover:bg-black/[0.025] dark:hover:bg-white/[0.03] focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-[#0071E3] dark:focus-visible:ring-[#0A84FF]">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-xs font-medium text-[#6E6E73] dark:text-[#98989D]">Unidades en stock</p>
                        <svg class="h-3.5 w-3.5 text-[#86868B] dark:text-[#6E6E73] transition-transform duration-150 group-hover:translate-x-0.5 group-hover:text-[#0071E3] dark:group-hover:text-[#0A84FF]" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 .02-1.06L11.168 10 7.23 6.29a.75.75 0 1 1 1.04-1.08l4.5 4.25a.75.75 0 0 1 0 1.08l-4.5 4.25a.75.75 0 0 1-1.06-.02Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="mt-1.5 text-2xl font-semibold tracking-[-0.02em] text-[#1D1D1F] dark:text-[#F5F5F7]">{{ number_format($totalUnits) }}</p>
                    <p class="mt-1 text-xs text-[#6E6E73] dark:text-[#98989D]">Existencias totales</p>
                </a>

                <a href="{{ route('products.index', ['stock_status' => 'low']) }}" class="group p-5 transition-colors duration-150 hover:bg-black/[0.025] dark:hover:bg-white/[0.03] focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-[#0071E3] dark:focus-visible:ring-[#0A84FF]">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-xs font-medium text-[#6E6E73] dark:text-[#98989D]">Alertas de stock bajo</p>
                        <svg class="h-3.5 w-3.5 text-[#86868B] dark:text-[#6E6E73] transition-transform duration-150 group-hover:translate-x-0.5 group-hover:text-[#0071E3] dark:group-hover:text-[#0A84FF]" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 .02-1.06L11.168 10 7.23 6.29a.75.75 0 1 1 1.04-1.08l4.5 4.25a.75.75 0 0 1 0 1.08l-4.5 4.25a.75.75 0 0 1-1.06-.02Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="mt-1.5 text-2xl font-semibold tracking-[-0.02em] {{ $lowStockCount > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-[#1D1D1F] dark:text-[#F5F5F7]' }}">
                        {{ $lowStockCount }}
                    </p>
                    <p class="mt-1 text-xs text-[#6E6E73] dark:text-[#98989D]">En o por debajo del mínimo</p>
                </a>

                <a href="{{ route('movements.index') }}" class="group p-5 transition-colors duration-150 hover:bg-black/[0.025] dark:hover:bg-white/[0.03] focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-[#0071E3] dark:focus-visible:ring-[#0A84FF]">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-xs font-medium text-[#6E6E73] dark:text-[#98989D]">Movimientos totales</p>
                        <svg class="h-3.5 w-3.5 text-[#86868B] dark:text-[#6E6E73] transition-transform duration-150 group-hover:translate-x-0.5 group-hover:text-[#0071E3] dark:group-hover:text-[#0A84FF]" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 0 1 .02-1.06L11.168 10 7.23 6.29a.75.75 0 1 1 1.04-1.08l4.5 4.25a.75.75 0 0 1 0 1.08l-4.5 4.25a.75.75 0 0 1-1.06-.02Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="mt-1.5 text-2xl font-semibold tracking-[-0.02em] text-[#1D1D1F] dark:text-[#F5F5F7]">{{ $totalMovements }}</p>
                    <p class="mt-1 text-xs text-[#6E6E73] dark:text-[#98989D]">Historial de entradas y salidas</p>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <section class="lg:col-span-5 apple-surface p-6">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="text-base font-semibold tracking-[-0.015em] text-[#1D1D1F] dark:text-[#F5F5F7]">
                            Stock bajo o agotado
                        </h2>
                        <a href="{{ route('products.index', ['stock_status' => 'low']) }}" class="text-xs font-medium text-[#0071E3] dark:text-[#0A84FF] hover:underline">
                            Ver catálogo
                        </a>
                    </div>

                    @if ($lowStockProducts->isEmpty())
                        <div class="mt-5 py-8 text-center">
                            <p class="text-sm font-medium text-[#1D1D1F] dark:text-[#F5F5F7]">Todo el inventario está saludable</p>
                            <p class="mt-1 text-xs text-[#6E6E73] dark:text-[#98989D]">Ningún producto está por debajo de su mínimo configurado.</p>
                        </div>
                    @else
                        <ul class="mt-4 divide-y divide-black/[0.06] dark:divide-white/[0.08]">
                            @foreach ($lowStockProducts as $item)
                                <li class="py-3 flex items-center justify-between gap-3">
                                    <div class="min-w-0">
                                        <a href="{{ route('products.show', $item) }}" class="block text-sm font-medium text-[#1D1D1F] dark:text-[#F5F5F7] hover:text-[#0071E3] dark:hover:text-[#0A84FF] truncate">
                                            {{ $item->name }}
                                        </a>
                                        <p class="text-xs text-[#6E6E73] dark:text-[#98989D] tabular-nums">
                                            {{ $item->sku }} · Mínimo {{ $item->min_stock }}
                                        </p>
                                    </div>

                                    <div class="shrink-0 flex items-center gap-2.5">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border tabular-nums {{ $item->stockBadgeClasses() }}">
                                            {{ $item->stock }} u.
                                        </span>
                                        <a href="{{ route('movements.create', ['product_id' => $item->id]) }}" class="text-xs font-medium text-[#0071E3] dark:text-[#0A84FF] hover:underline">
                                            Reponer
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>

                <section class="lg:col-span-7 apple-surface p-6">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="text-base font-semibold tracking-[-0.015em] text-[#1D1D1F] dark:text-[#F5F5F7]">
                            Movimientos recientes
                        </h2>
                        <a href="{{ route('movements.index') }}" class="text-xs font-medium text-[#0071E3] dark:text-[#0A84FF] hover:underline">
                            Ver historial completo
                        </a>
                    </div>

                    @if ($recentMovements->isEmpty())
                        <div class="mt-5 py-8 text-center">
                            <p class="text-sm font-medium text-[#1D1D1F] dark:text-[#F5F5F7]">Aún no hay movimientos registrados</p>
                            <p class="mt-1 text-xs text-[#6E6E73] dark:text-[#98989D]">Registra una entrada o salida para comenzar el kardex.</p>
                        </div>
                    @else
                        <ul class="mt-4 divide-y divide-black/[0.06] dark:divide-white/[0.08]">
                            @foreach ($recentMovements as $movement)
                                <li class="py-3 flex items-center justify-between gap-4">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium border {{ $movement->type->badgeClasses() }}">
                                                {{ $movement->type->label() }}
                                            </span>
                                            <a href="{{ route('products.show', $movement->product) }}" class="text-sm font-medium text-[#1D1D1F] dark:text-[#F5F5F7] hover:text-[#0071E3] dark:hover:text-[#0A84FF] truncate">
                                                {{ $movement->product->name }}
                                            </a>
                                        </div>
                                        <p class="mt-0.5 text-xs text-[#6E6E73] dark:text-[#98989D] truncate">
                                            {{ $movement->user->name }} · {{ $movement->reason ?? 'Sin motivo especificado' }}
                                        </p>
                                    </div>

                                    <div class="shrink-0 text-right tabular-nums">
                                        <p class="text-sm font-semibold text-[#1D1D1F] dark:text-[#F5F5F7]">
                                            {{ $movement->signedDeltaLabel() }}
                                        </p>
                                        <p class="text-xs text-[#6E6E73] dark:text-[#98989D]">
                                            {{ $movement->previous_stock }} → {{ $movement->new_stock }}
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>
            </div>

            @can('manage-users')
                <section class="apple-surface px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 tabular-nums">
                    <p class="text-sm text-[#6E6E73] dark:text-[#98989D]">
                        Equipo registrado: <span class="font-medium text-[#1D1D1F] dark:text-[#F5F5F7]">{{ $totalUsers }}</span> cuentas ({{ $adminCount }} {{ $adminCount === 1 ? 'administrador' : 'administradores' }}, {{ $vendedorCount }} {{ $vendedorCount === 1 ? 'vendedor' : 'vendedores' }}).
                    </p>
                    <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-[#0071E3] dark:text-[#0A84FF] hover:underline">
                        Administrar usuarios y roles
                    </a>
                </section>
            @endcan
        </div>
    </div>
</x-app-layout>
