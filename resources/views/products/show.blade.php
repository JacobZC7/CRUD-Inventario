<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-[-0.025em] text-[#1D1D1F] dark:text-[#F5F5F7]">
                    {{ $product->name }}
                </h1>
                <p class="mt-1 text-sm text-[#6E6E73] dark:text-[#98989D]">
                    <span class="font-mono">{{ $product->sku }}</span>
                    · {{ $product->category->name }}
                    · {{ $product->is_active ? 'Activo en catálogo' : 'Inactivo' }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('products.index') }}" class="apple-press inline-flex items-center justify-center min-h-[44px] sm:min-h-[40px] px-4 py-2 rounded-xl bg-white hover:bg-[#F5F5F7] dark:bg-[#2C2C2E] dark:hover:bg-[#3A3A3C] border border-black/15 dark:border-white/15 text-sm font-medium text-[#1D1D1F] dark:text-[#F5F5F7]">
                    Volver al catálogo
                </a>

                @can('manage-products')
                    <a href="{{ route('products.edit', $product) }}" class="apple-press inline-flex items-center justify-center min-h-[44px] sm:min-h-[40px] px-4 py-2 rounded-xl bg-[#0071E3] hover:bg-[#0077ED] dark:bg-[#0A84FF] dark:hover:bg-[#3395FF] text-sm font-medium text-white">
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
                <div class="rounded-2xl bg-emerald-50 dark:bg-emerald-500/15 border border-emerald-600/20 dark:border-emerald-400/25 px-4 py-3 text-sm text-emerald-800 dark:text-emerald-300">
                    {{ session('status') }}
                </div>
            @endif

            <section class="apple-surface overflow-hidden">
                <div class="grid grid-cols-2 lg:grid-cols-4 divide-y lg:divide-y-0 lg:divide-x divide-black/[0.06] dark:divide-white/[0.08] tabular-nums">
                    <div class="p-5">
                        <p class="text-xs font-medium text-[#6E6E73] dark:text-[#98989D]">Stock actual</p>
                        <div class="mt-1.5 flex items-center gap-2.5">
                            <span class="text-2xl font-semibold tracking-[-0.02em] text-[#1D1D1F] dark:text-[#F5F5F7]">{{ $product->stock }}</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $product->stockBadgeClasses() }}">
                                {{ $product->stockStatusLabel() }}
                            </span>
                        </div>
                        <p class="mt-1 text-xs text-[#6E6E73] dark:text-[#98989D]">Mínimo configurado: {{ $product->min_stock }} u.</p>
                    </div>

                    <div class="p-5">
                        <p class="text-xs font-medium text-[#6E6E73] dark:text-[#98989D]">Precio de venta</p>
                        <p class="mt-1.5 text-2xl font-semibold tracking-[-0.02em] text-[#1D1D1F] dark:text-[#F5F5F7]">
                            ${{ number_format((float) $product->price, 2) }}
                        </p>
                        <p class="mt-1 text-xs text-[#6E6E73] dark:text-[#98989D]">Precio unitario al público</p>
                    </div>

                    <div class="p-5">
                        <p class="text-xs font-medium text-[#6E6E73] dark:text-[#98989D]">Costo unitario</p>
                        <p class="mt-1.5 text-2xl font-semibold tracking-[-0.02em] text-[#1D1D1F] dark:text-[#F5F5F7]">
                            ${{ number_format((float) $product->cost, 2) }}
                        </p>
                        <p class="mt-1 text-xs text-[#6E6E73] dark:text-[#98989D]">Costo de reposición</p>
                    </div>

                    <div class="p-5">
                        <p class="text-xs font-medium text-[#6E6E73] dark:text-[#98989D]">Margen estimado</p>
                        <p class="mt-1.5 text-2xl font-semibold tracking-[-0.02em] {{ $margin >= 0 ? 'text-[#1D1D1F] dark:text-[#F5F5F7]' : 'text-rose-600 dark:text-rose-400' }}">
                            ${{ number_format($margin, 2) }}
                        </p>
                        <p class="mt-1 text-xs text-[#6E6E73] dark:text-[#98989D]">
                            @if ($marginPercent !== null)
                                {{ number_format($marginPercent, 1) }}% sobre costo
                            @else
                                Sin costo registrado
                            @endif
                        </p>
                    </div>
                </div>

                @if ($product->description)
                    <div class="px-5 py-4 border-t border-black/[0.06] dark:border-white/[0.08] bg-[#F5F5F7]/40 dark:bg-white/[0.02]">
                        <p class="text-xs font-medium text-[#6E6E73] dark:text-[#98989D]">Descripción</p>
                        <p class="mt-1 text-sm text-[#1D1D1F] dark:text-[#F5F5F7]">{{ $product->description }}</p>
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
                        submitting: false,
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
                    <h2 class="text-base font-semibold tracking-[-0.015em] text-[#1D1D1F] dark:text-[#F5F5F7]">
                        Movimiento rápido
                    </h2>
                    <p class="mt-1 text-xs text-[#6E6E73] dark:text-[#98989D]">
                        Registra una entrada, salida o ajuste directo para este producto.
                    </p>

                    <form method="POST" action="{{ route('movements.store') }}" @submit="submitting = true" class="mt-4 space-y-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="redirect_to" value="product">

                        <div>
                            <x-input-label for="quick_type" :value="__('Tipo de movimiento')" />
                            <select
                                id="quick_type"
                                name="type"
                                x-model="type"
                                class="mt-1.5 w-full min-h-[44px] sm:min-h-[40px] rounded-xl border border-black/15 dark:border-white/15 bg-white dark:bg-[#2C2C2E] py-2.5 px-3 text-sm text-[#1D1D1F] dark:text-[#F5F5F7] focus:border-[#0071E3] dark:focus:border-[#0A84FF] focus:outline-none focus:ring-2 focus:ring-[#0071E3]/20 dark:focus:ring-[#0A84FF]/25"
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
                            <label for="quick_quantity" class="block text-sm font-medium text-[#1D1D1F] dark:text-[#F5F5F7]" x-text="quantityLabel"></label>
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

                        <div class="rounded-xl bg-[#F5F5F7] dark:bg-[#2C2C2E] border border-black/[0.06] dark:border-white/[0.08] px-3.5 py-2.5 flex items-center justify-between text-xs tabular-nums">
                            <span class="text-[#6E6E73] dark:text-[#98989D]">Stock resultante</span>
                            <span
                                class="font-semibold"
                                :class="resultingStock < 0 ? 'text-rose-600 dark:text-rose-400' : 'text-[#1D1D1F] dark:text-[#F5F5F7]'"
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

                        <x-primary-button class="w-full justify-center" x-bind:disabled="submitting">
                            <span x-show="!submitting">Registrar movimiento</span>
                            <span x-show="submitting" x-cloak>Registrando...</span>
                        </x-primary-button>
                    </form>
                </section>

                <section class="lg:col-span-8 apple-surface overflow-hidden">
                    <div class="px-5 py-4 border-b border-black/[0.06] dark:border-white/[0.08] flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-base font-semibold tracking-[-0.015em] text-[#1D1D1F] dark:text-[#F5F5F7]">
                                Kardex del producto
                            </h2>
                            <p class="mt-0.5 text-xs text-[#6E6E73] dark:text-[#98989D]">
                                Trazabilidad de entradas, salidas y ajustes ordenados del más reciente al más antiguo.
                            </p>
                        </div>

                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[#F5F5F7] dark:bg-white/[0.08] text-[#6E6E73] dark:text-[#98989D] border border-black/[0.06] dark:border-white/[0.08] tabular-nums">
                            {{ $movements->total() }} {{ $movements->total() === 1 ? 'registro' : 'registros' }}
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-black/[0.06] dark:divide-white/[0.08]">
                            <thead>
                                <tr class="bg-[#F5F5F7]/70 dark:bg-white/[0.04]">
                                    <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-[#6E6E73] dark:text-[#98989D]">Fecha</th>
                                    <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-[#6E6E73] dark:text-[#98989D]">Tipo</th>
                                    <th scope="col" class="px-5 py-3 text-right text-xs font-medium text-[#6E6E73] dark:text-[#98989D]">Cambio</th>
                                    <th scope="col" class="px-5 py-3 text-right text-xs font-medium text-[#6E6E73] dark:text-[#98989D]">Stock</th>
                                    <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-[#6E6E73] dark:text-[#98989D]">Motivo</th>
                                    <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-[#6E6E73] dark:text-[#98989D]">Usuario</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-black/[0.06] dark:divide-white/[0.08] bg-white dark:bg-[#1C1C1E]">
                                @forelse ($movements as $movement)
                                    <tr class="apple-row-hover">
                                        <td class="px-5 py-3 whitespace-nowrap text-xs text-[#6E6E73] dark:text-[#98989D] tabular-nums">
                                            {{ $movement->created_at?->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-5 py-3 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium border {{ $movement->type->badgeClasses() }}">
                                                {{ $movement->type->label() }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-3 whitespace-nowrap text-right text-sm font-semibold text-[#1D1D1F] dark:text-[#F5F5F7] tabular-nums">
                                            {{ $movement->signedDeltaLabel() }}
                                        </td>
                                        <td class="px-5 py-3 whitespace-nowrap text-right text-xs text-[#6E6E73] dark:text-[#98989D] tabular-nums">
                                            {{ $movement->previous_stock }} → <span class="font-semibold text-[#1D1D1F] dark:text-[#F5F5F7]">{{ $movement->new_stock }}</span>
                                        </td>
                                        <td class="px-5 py-3 text-xs text-[#1D1D1F] dark:text-[#F5F5F7] max-w-xs truncate">
                                            {{ $movement->reason ?? '—' }}
                                        </td>
                                        <td class="px-5 py-3 whitespace-nowrap text-xs text-[#6E6E73] dark:text-[#98989D]">
                                            {{ $movement->user->name }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-5 py-10 text-center text-sm text-[#6E6E73] dark:text-[#98989D]">
                                            Este producto aún no tiene movimientos registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($movements->hasPages())
                        <div class="px-5 py-3.5 border-t border-black/[0.06] dark:border-white/[0.08] bg-[#F5F5F7]/40 dark:bg-white/[0.02] tabular-nums">
                            {{ $movements->links() }}
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
