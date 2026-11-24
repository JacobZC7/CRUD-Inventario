<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-[-0.025em] text-[#1D1D1F]">
                    Panel de inventario
                </h1>
                <p class="mt-1 text-sm text-[#6E6E73]">
                    Hola, {{ auth()->user()->name }}. Estado actual del catálogo, existencias y movimientos.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('movements.create') }}" class="apple-press inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white hover:bg-[#F5F5F7] border border-black/15 text-sm font-medium text-[#1D1D1F]">
                    Registrar movimiento
                </a>

                @can('manage-products')
                    <a href="{{ route('products.create') }}" class="apple-press inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#0071E3] hover:bg-[#0077ED] text-sm font-medium text-white">
                        Nuevo producto
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="apple-surface grid grid-cols-2 lg:grid-cols-4 divide-y lg:divide-y-0 lg:divide-x divide-black/[0.06] tabular-nums">
                <div class="p-5">
                    <p class="text-xs font-medium text-[#6E6E73]">Productos registrados</p>
                    <p class="mt-1.5 text-2xl font-semibold tracking-[-0.02em] text-[#1D1D1F]">{{ $totalProducts }}</p>
                    <p class="mt-1 text-xs text-[#6E6E73]">{{ $activeProducts }} activos en catálogo</p>
                </div>

                <div class="p-5">
                    <p class="text-xs font-medium text-[#6E6E73]">Unidades en stock</p>
                    <p class="mt-1.5 text-2xl font-semibold tracking-[-0.02em] text-[#1D1D1F]">{{ number_format($totalUnits) }}</p>
                    <p class="mt-1 text-xs text-[#6E6E73]">Existencias totales</p>
                </div>

                <div class="p-5">
                    <p class="text-xs font-medium text-[#6E6E73]">Alertas de stock bajo</p>
                    <p class="mt-1.5 text-2xl font-semibold tracking-[-0.02em] {{ $lowStockCount > 0 ? 'text-amber-600' : 'text-[#1D1D1F]' }}">
                        {{ $lowStockCount }}
                    </p>
                    <p class="mt-1 text-xs text-[#6E6E73]">En o por debajo del mínimo</p>
                </div>

                <div class="p-5">
                    <p class="text-xs font-medium text-[#6E6E73]">Movimientos totales</p>
                    <p class="mt-1.5 text-2xl font-semibold tracking-[-0.02em] text-[#1D1D1F]">{{ $totalMovements }}</p>
                    <p class="mt-1 text-xs text-[#6E6E73]">Historial de entradas y salidas</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <section class="lg:col-span-5 apple-surface p-6">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="text-base font-semibold tracking-[-0.015em] text-[#1D1D1F]">
                            Stock bajo o agotado
                        </h2>
                        <a href="{{ route('products.index', ['stock_status' => 'low']) }}" class="text-xs font-medium text-[#0071E3] hover:underline">
                            Ver catálogo
                        </a>
                    </div>

                    @if ($lowStockProducts->isEmpty())
                        <div class="mt-5 py-8 text-center">
                            <p class="text-sm font-medium text-[#1D1D1F]">Todo el inventario está saludable</p>
                            <p class="mt-1 text-xs text-[#6E6E73]">Ningún producto está por debajo de su mínimo configurado.</p>
                        </div>
                    @else
                        <ul class="mt-4 divide-y divide-black/[0.06]">
                            @foreach ($lowStockProducts as $item)
                                <li class="py-3 flex items-center justify-between gap-3">
                                    <div class="min-w-0">
                                        <a href="{{ route('products.show', $item) }}" class="block text-sm font-medium text-[#1D1D1F] hover:text-[#0071E3] truncate">
                                            {{ $item->name }}
                                        </a>
                                        <p class="text-xs text-[#6E6E73] tabular-nums">
                                            {{ $item->sku }} · Mínimo {{ $item->min_stock }}
                                        </p>
                                    </div>

                                    <div class="shrink-0 flex items-center gap-2.5">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border tabular-nums {{ $item->stockBadgeClasses() }}">
                                            {{ $item->stock }} u.
                                        </span>
                                        <a href="{{ route('movements.create', ['product_id' => $item->id]) }}" class="text-xs font-medium text-[#0071E3] hover:underline">
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
                        <h2 class="text-base font-semibold tracking-[-0.015em] text-[#1D1D1F]">
                            Movimientos recientes
                        </h2>
                        <a href="{{ route('movements.index') }}" class="text-xs font-medium text-[#0071E3] hover:underline">
                            Ver historial completo
                        </a>
                    </div>

                    @if ($recentMovements->isEmpty())
                        <div class="mt-5 py-8 text-center">
                            <p class="text-sm font-medium text-[#1D1D1F]">Aún no hay movimientos registrados</p>
                            <p class="mt-1 text-xs text-[#6E6E73]">Registra una entrada o salida para comenzar el kardex.</p>
                        </div>
                    @else
                        <ul class="mt-4 divide-y divide-black/[0.06]">
                            @foreach ($recentMovements as $movement)
                                <li class="py-3 flex items-center justify-between gap-4">
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium border {{ $movement->type->badgeClasses() }}">
                                                {{ $movement->type->label() }}
                                            </span>
                                            <a href="{{ route('products.show', $movement->product) }}" class="text-sm font-medium text-[#1D1D1F] hover:text-[#0071E3] truncate">
                                                {{ $movement->product->name }}
                                            </a>
                                        </div>
                                        <p class="mt-0.5 text-xs text-[#6E6E73] truncate">
                                            {{ $movement->user->name }} · {{ $movement->reason ?? 'Sin motivo especificado' }}
                                        </p>
                                    </div>

                                    <div class="shrink-0 text-right tabular-nums">
                                        <p class="text-sm font-semibold text-[#1D1D1F]">
                                            {{ $movement->signedDeltaLabel() }}
                                        </p>
                                        <p class="text-xs text-[#6E6E73]">
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
                    <p class="text-sm text-[#6E6E73]">
                        Equipo registrado: <span class="font-medium text-[#1D1D1F]">{{ $totalUsers }}</span> cuentas ({{ $adminCount }} {{ $adminCount === 1 ? 'administrador' : 'administradores' }}, {{ $vendedorCount }} {{ $vendedorCount === 1 ? 'vendedor' : 'vendedores' }}).
                    </p>
                    <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-[#0071E3] hover:underline">
                        Administrar usuarios y roles
                    </a>
                </section>
            @endcan
        </div>
    </div>
</x-app-layout>
