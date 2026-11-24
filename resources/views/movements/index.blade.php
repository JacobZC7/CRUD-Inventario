<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-[-0.025em] text-[#1D1D1F]">
                    Movimientos de inventario
                </h1>
                <p class="mt-1 text-sm text-[#6E6E73]">
                    Historial completo de entradas, salidas y ajustes con usuario responsable.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2 self-start sm:self-auto">
                <a href="{{ route('movements.export', request()->only(['product_id', 'type'])) }}" class="apple-press inline-flex items-center justify-center min-h-[44px] sm:min-h-[40px] px-3.5 py-2 rounded-xl bg-white hover:bg-[#F5F5F7] border border-black/15 text-sm font-medium text-[#1D1D1F] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3]">
                    Exportar CSV
                </a>

                <a href="{{ route('movements.create') }}" class="apple-press inline-flex items-center justify-center min-h-[44px] sm:min-h-[40px] px-4 py-2 rounded-xl bg-[#0071E3] hover:bg-[#0077ED] text-sm font-medium text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] focus-visible:ring-offset-2">
                    Registrar movimiento
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">
            @if (session('status'))
                <div class="rounded-2xl bg-emerald-50 border border-emerald-600/20 px-4 py-3 text-sm text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            <form method="GET" action="{{ route('movements.index') }}" class="apple-surface p-4 flex flex-col md:flex-row items-stretch md:items-center gap-3">
                <div class="flex-1">
                    <select name="product_id" aria-label="Filtrar por producto" class="w-full min-h-[44px] sm:min-h-[40px] rounded-xl border border-black/15 bg-white py-2.5 px-3 text-sm text-[#1D1D1F] focus:border-[#0071E3] focus:outline-none focus:ring-2 focus:ring-[#0071E3]/20">
                        <option value="">Todos los productos</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @selected($filters['product_id'] === $product->id)>
                                {{ $product->name }} ({{ $product->sku }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full md:w-48">
                    <select name="type" aria-label="Filtrar por tipo de movimiento" class="w-full min-h-[44px] sm:min-h-[40px] rounded-xl border border-black/15 bg-white py-2.5 px-3 text-sm text-[#1D1D1F] focus:border-[#0071E3] focus:outline-none focus:ring-2 focus:ring-[#0071E3]/20">
                        <option value="">Todos los tipos</option>
                        @foreach ($movementTypes as $movementType)
                            <option value="{{ $movementType->value }}" @selected($filters['type'] === $movementType->value)>
                                {{ $movementType->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <x-primary-button type="submit">
                        Filtrar
                    </x-primary-button>

                    @if ($filters['product_id'] > 0 || $filters['type'] !== '')
                        <a href="{{ route('movements.index') }}" class="apple-press inline-flex items-center justify-center min-h-[44px] sm:min-h-[40px] px-3.5 py-2.5 rounded-xl bg-white hover:bg-[#F5F5F7] border border-black/15 text-sm font-medium text-[#6E6E73]">
                            Limpiar
                        </a>
                    @endif
                </div>
            </form>

            <div class="apple-surface overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-black/[0.06]">
                        <thead>
                            <tr class="bg-[#F5F5F7]/70">
                                <th scope="col" class="px-5 py-3.5 text-left text-xs font-medium text-[#6E6E73]">Fecha</th>
                                <th scope="col" class="px-5 py-3.5 text-left text-xs font-medium text-[#6E6E73]">Producto</th>
                                <th scope="col" class="px-5 py-3.5 text-left text-xs font-medium text-[#6E6E73]">Tipo</th>
                                <th scope="col" class="px-5 py-3.5 text-right text-xs font-medium text-[#6E6E73]">Variación</th>
                                <th scope="col" class="px-5 py-3.5 text-right text-xs font-medium text-[#6E6E73]">Stock</th>
                                <th scope="col" class="px-5 py-3.5 text-left text-xs font-medium text-[#6E6E73]">Motivo</th>
                                <th scope="col" class="px-5 py-3.5 text-left text-xs font-medium text-[#6E6E73]">Usuario</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/[0.06] bg-white">
                            @forelse ($movements as $movement)
                                <tr class="apple-row-hover">
                                    <td class="px-5 py-3.5 whitespace-nowrap text-xs text-[#6E6E73] tabular-nums">
                                        {{ $movement->created_at?->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <a href="{{ route('products.show', $movement->product) }}" class="text-sm font-medium text-[#1D1D1F] hover:text-[#0071E3]">
                                            {{ $movement->product->name }}
                                        </a>
                                        <p class="text-xs font-mono text-[#6E6E73]">
                                            {{ $movement->product->sku }}
                                        </p>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $movement->type->badgeClasses() }}">
                                            {{ $movement->type->label() }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-right text-sm font-semibold text-[#1D1D1F] tabular-nums">
                                        {{ $movement->signedDeltaLabel() }}
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-right text-xs text-[#6E6E73] tabular-nums">
                                        {{ $movement->previous_stock }} → <span class="font-semibold text-[#1D1D1F]">{{ $movement->new_stock }}</span>
                                    </td>
                                    <td class="px-5 py-3.5 text-xs text-[#1D1D1F] max-w-xs truncate">
                                        {{ $movement->reason ?? '—' }}
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-xs text-[#6E6E73]">
                                        {{ $movement->user->name }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-12 text-center">
                                        <p class="text-sm font-medium text-[#1D1D1F]">No hay movimientos con los filtros actuales</p>
                                        <p class="mt-1 text-xs text-[#6E6E73]">
                                            Registra una entrada, salida o ajuste para empezar el historial.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($movements->hasPages())
                    <div class="px-5 py-3.5 border-t border-black/[0.06] bg-[#F5F5F7]/40 tabular-nums">
                        {{ $movements->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
