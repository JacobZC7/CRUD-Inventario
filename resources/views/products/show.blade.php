<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-[-0.025em] text-[#1D1D1F]">
                    {{ $product->name }}
                </h1>
                <p class="mt-1 text-sm text-[#6E6E73]">
                    <span class="font-mono">{{ $product->sku }}</span>
                    · {{ $product->category->name }}
                    · {{ $product->is_active ? 'Activo en catálogo' : 'Inactivo' }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('products.index') }}" class="apple-press inline-flex items-center px-4 py-2 rounded-xl bg-white hover:bg-[#F5F5F7] border border-black/15 text-sm font-medium text-[#1D1D1F]">
                    Volver al catálogo
                </a>

                @can('manage-products')
                    <a href="{{ route('products.edit', $product) }}" class="apple-press inline-flex items-center px-4 py-2 rounded-xl bg-[#0071E3] hover:bg-[#0077ED] text-sm font-medium text-white">
                        Editar producto
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    @php
        $margin = (float) $product->price - (float) $product->cost;
        $marginPercent = (float) $product->cost > 0 ? ($margin / (float) $product->cost) * 100 : null;
    @endphp

    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="rounded-2xl bg-emerald-50 border border-emerald-600/20 px-4 py-3 text-sm text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            <section class="apple-surface overflow-hidden">
                <div class="grid grid-cols-2 lg:grid-cols-4 divide-y lg:divide-y-0 lg:divide-x divide-black/[0.06] tabular-nums">
                    <div class="p-5">
                        <p class="text-xs font-medium text-[#6E6E73]">Stock actual</p>
                        <div class="mt-1.5 flex items-center gap-2.5">
                            <span class="text-2xl font-semibold tracking-[-0.02em] text-[#1D1D1F]">{{ $product->stock }}</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $product->stockBadgeClasses() }}">
                                {{ $product->stockStatusLabel() }}
                            </span>
                        </div>
                        <p class="mt-1 text-xs text-[#6E6E73]">Mínimo configurado: {{ $product->min_stock }} u.</p>
                    </div>

                    <div class="p-5">
                        <p class="text-xs font-medium text-[#6E6E73]">Precio de venta</p>
                        <p class="mt-1.5 text-2xl font-semibold tracking-[-0.02em] text-[#1D1D1F]">
                            ${{ number_format((float) $product->price, 2) }}
                        </p>
                        <p class="mt-1 text-xs text-[#6E6E73]">Precio unitario al público</p>
                    </div>

                    <div class="p-5">
                        <p class="text-xs font-medium text-[#6E6E73]">Costo unitario</p>
                        <p class="mt-1.5 text-2xl font-semibold tracking-[-0.02em] text-[#1D1D1F]">
                            ${{ number_format((float) $product->cost, 2) }}
                        </p>
                        <p class="mt-1 text-xs text-[#6E6E73]">Costo de reposición</p>
                    </div>

                    <div class="p-5">
                        <p class="text-xs font-medium text-[#6E6E73]">Margen estimado</p>
                        <p class="mt-1.5 text-2xl font-semibold tracking-[-0.02em] {{ $margin >= 0 ? 'text-[#1D1D1F]' : 'text-rose-600' }}">
                            ${{ number_format($margin, 2) }}
                        </p>
                        <p class="mt-1 text-xs text-[#6E6E73]">
                            @if ($marginPercent !== null)
                                {{ number_format($marginPercent, 1) }}% sobre costo
                            @else
                                Sin costo registrado
                            @endif
                        </p>
                    </div>
                </div>

                @if ($product->description)
                    <div class="px-5 py-4 border-t border-black/[0.06] bg-[#F5F5F7]/40">
                        <p class="text-xs font-medium text-[#6E6E73]">Descripción</p>
                        <p class="mt-1 text-sm text-[#1D1D1F]">{{ $product->description }}</p>
                    </div>
                @endif
            </section>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <section
                    class="lg:col-span-4 apple-surface p-6 self-start"
                    x-data="{
                        type: @js(old('type', 'entrada')),
                        quantity: @js((int) old('quantity', 1)),
                        currentStock: {{ $product->stock }},
                        get resultingStock() {
                            const qty = Number(this.quantity) || 0;
                            if (this.type === 'entrada') return this.currentStock + qty;
                            if (this.type === 'salida') return this.currentStock - qty;
                            return qty;
                        },
                        get quantityLabel() {
                            return this.type === 'ajuste' ? 'Nuevo stock físico' : 'Cantidad';
                        }
                    }"
                >
                    <h2 class="text-base font-semibold tracking-[-0.015em] text-[#1D1D1F]">
                        Movimiento rápido
                    </h2>
                    <p class="mt-1 text-xs text-[#6E6E73]">
                        Registra una entrada, salida o ajuste directo para este producto.
                    </p>

                    <form method="POST" action="{{ route('movements.store') }}" class="mt-4 space-y-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="redirect_to" value="product">

                        <div>
                            <x-input-label for="quick_type" :value="__('Tipo de movimiento')" />
                            <select
                                id="quick_type"
                                name="type"
                                x-model="type"
                                class="mt-1.5 w-full rounded-xl border border-black/15 bg-white py-2.5 px-3 text-sm text-[#1D1D1F] focus:border-[#0071E3] focus:outline-none focus:ring-2 focus:ring-[#0071E3]/20"
                            >
                                @foreach ($movementTypes as $movementType)
                                    <option value="{{ $movementType->value }}">
                                        {{ $movementType->label() }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-1.5" />
                        </div>

                        <div>
                            <label for="quick_quantity" class="block text-sm font-medium text-[#1D1D1F]" x-text="quantityLabel"></label>
                            <x-text-input
                                id="quick_quantity"
                                name="quantity"
                                type="number"
                                x-bind:min="type === 'ajuste' ? 0 : 1"
                                x-model.number="quantity"
                                class="mt-1.5 tabular-nums"
                                required
                            />
                            <x-input-error :messages="$errors->get('quantity')" class="mt-1.5" />
                        </div>

                        <div class="rounded-xl bg-[#F5F5F7] border border-black/[0.06] px-3.5 py-2.5 flex items-center justify-between text-xs tabular-nums">
                            <span class="text-[#6E6E73]">Stock resultante</span>
                            <span
                                class="font-semibold"
                                :class="resultingStock < 0 ? 'text-rose-600' : 'text-[#1D1D1F]'"
                                x-text="resultingStock + ' unidades'"
                            ></span>
                        </div>

                        <div>
                            <x-input-label for="quick_reason" :value="__('Motivo (opcional)')" />
                            <x-text-input
                                id="quick_reason"
                                name="reason"
                                type="text"
                                class="mt-1.5"
                                :value="old('reason')"
                                placeholder="Ej. Venta en caja, reposición"
                            />
                            <x-input-error :messages="$errors->get('reason')" class="mt-1.5" />
                        </div>

                        <x-primary-button class="w-full justify-center">
                            Registrar movimiento
                        </x-primary-button>
                    </form>
                </section>

                <section class="lg:col-span-8 apple-surface overflow-hidden">
                    <div class="px-5 py-4 border-b border-black/[0.06] flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-base font-semibold tracking-[-0.015em] text-[#1D1D1F]">
                                Kardex del producto
                            </h2>
                            <p class="mt-0.5 text-xs text-[#6E6E73]">
                                Trazabilidad de entradas, salidas y ajustes ordenados del más reciente al más antiguo.
                            </p>
                        </div>

                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[#F5F5F7] text-[#6E6E73] border border-black/[0.06] tabular-nums">
                            {{ $movements->total() }} {{ $movements->total() === 1 ? 'registro' : 'registros' }}
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-black/[0.06]">
                            <thead>
                                <tr class="bg-[#F5F5F7]/70">
                                    <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-[#6E6E73]">Fecha</th>
                                    <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-[#6E6E73]">Tipo</th>
                                    <th scope="col" class="px-5 py-3 text-right text-xs font-medium text-[#6E6E73]">Cambio</th>
                                    <th scope="col" class="px-5 py-3 text-right text-xs font-medium text-[#6E6E73]">Stock</th>
                                    <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-[#6E6E73]">Motivo</th>
                                    <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-[#6E6E73]">Usuario</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-black/[0.06] bg-white">
                                @forelse ($movements as $movement)
                                    <tr class="apple-row-hover">
                                        <td class="px-5 py-3 whitespace-nowrap text-xs text-[#6E6E73] tabular-nums">
                                            {{ $movement->created_at?->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-5 py-3 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium border {{ $movement->type->badgeClasses() }}">
                                                {{ $movement->type->label() }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-3 whitespace-nowrap text-right text-sm font-semibold text-[#1D1D1F] tabular-nums">
                                            {{ $movement->signedDeltaLabel() }}
                                        </td>
                                        <td class="px-5 py-3 whitespace-nowrap text-right text-xs text-[#6E6E73] tabular-nums">
                                            {{ $movement->previous_stock }} → <span class="font-semibold text-[#1D1D1F]">{{ $movement->new_stock }}</span>
                                        </td>
                                        <td class="px-5 py-3 text-xs text-[#1D1D1F] max-w-xs truncate">
                                            {{ $movement->reason ?? '—' }}
                                        </td>
                                        <td class="px-5 py-3 whitespace-nowrap text-xs text-[#6E6E73]">
                                            {{ $movement->user->name }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-5 py-10 text-center text-sm text-[#6E6E73]">
                                            Este producto aún no tiene movimientos registrados.
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
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
