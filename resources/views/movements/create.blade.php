<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold tracking-[-0.025em] text-[#1D1D1F]">
                    Registrar movimiento
                </h1>
                <p class="mt-1 text-sm text-[#6E6E73]">
                    Actualiza el stock de un producto dejando registro en el kardex.
                </p>
            </div>

            <a href="{{ route('movements.index') }}" class="text-sm font-medium text-[#0071E3] hover:underline">
                Volver al historial
            </a>
        </div>
    </x-slot>

    @php
        $productCatalog = $products->mapWithKeys(fn ($item) => [
            $item->id => [
                'name' => $item->name,
                'sku' => $item->sku,
                'stock' => $item->stock,
                'min_stock' => $item->min_stock,
            ],
        ]);
        $initialProductId = (int) old('product_id', $selectedProductId ?: ($products->first()?->id ?? 0));
    @endphp

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($products->isEmpty())
                <div class="apple-surface p-8 text-center">
                    <p class="text-sm font-medium text-[#1D1D1F]">No hay productos activos disponibles</p>
                    <p class="mt-1 text-xs text-[#6E6E73]">
                        Activa o crea un producto en el catálogo antes de registrar movimientos.
                    </p>
                </div>
            @else
                <form
                    method="POST"
                    action="{{ route('movements.store') }}"
                    class="apple-surface p-6 sm:p-8 space-y-5"
                    x-data="{
                        catalog: @js($productCatalog),
                        productId: {{ $initialProductId }},
                        type: @js(old('type', 'entrada')),
                        quantity: @js((int) old('quantity', 1)),
                        get selectedProduct() {
                            return this.catalog[this.productId] ?? null;
                        },
                        get currentStock() {
                            return this.selectedProduct ? Number(this.selectedProduct.stock) : 0;
                        },
                        get resultingStock() {
                            const qty = Number(this.quantity) || 0;
                            if (this.type === 'entrada') return this.currentStock + qty;
                            if (this.type === 'salida') return this.currentStock - qty;
                            return qty;
                        },
                        get deltaLabel() {
                            const diff = this.resultingStock - this.currentStock;
                            return diff > 0 ? `+${diff}` : `${diff}`;
                        },
                        get quantityLabel() {
                            if (this.type === 'entrada') return 'Cantidad que ingresa';
                            if (this.type === 'salida') return 'Cantidad que sale';
                            return 'Nuevo stock físico total';
                        },
                        get hasStockWarning() {
                            if (this.type === 'salida') return (Number(this.quantity) || 0) > this.currentStock;
                            if (this.type === 'ajuste') return (Number(this.quantity) || 0) === this.currentStock;
                            return false;
                        }
                    }"
                >
                    @csrf

                    <div>
                        <x-input-label for="product_id" :value="__('Producto')" />
                        <select
                            id="product_id"
                            name="product_id"
                            x-model.number="productId"
                            required
                            class="mt-1.5 w-full rounded-xl border border-black/15 bg-white py-2.5 px-3 text-sm text-[#1D1D1F] focus:border-[#0071E3] focus:outline-none focus:ring-2 focus:ring-[#0071E3]/20"
                        >
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" @selected($initialProductId === $product->id)>
                                    {{ $product->name }} ({{ $product->sku }}) — Stock: {{ $product->stock }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('product_id')" class="mt-1.5" />
                    </div>

                    <div>
                        <span class="block text-sm font-medium text-[#1D1D1F]">Tipo de movimiento</span>
                        <div class="mt-1.5 grid grid-cols-3 gap-2.5">
                            @foreach ($movementTypes as $movementType)
                                <label
                                    class="apple-press cursor-pointer rounded-xl border px-3.5 py-2.5 text-center text-sm font-medium transition-colors duration-150"
                                    :class="type === '{{ $movementType->value }}' ? 'border-[#0071E3] bg-[#0071E3]/10 text-[#005BB5]' : 'border-black/15 bg-white text-[#1D1D1F] hover:bg-[#F5F5F7]'"
                                >
                                    <input
                                        type="radio"
                                        name="type"
                                        value="{{ $movementType->value }}"
                                        x-model="type"
                                        class="sr-only"
                                    >
                                    {{ $movementType->label() }}
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('type')" class="mt-1.5" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-[#1D1D1F]" x-text="quantityLabel"></label>
                            <x-text-input
                                id="quantity"
                                name="quantity"
                                type="number"
                                x-bind:min="type === 'ajuste' ? 0 : 1"
                                x-model.number="quantity"
                                class="mt-1.5 tabular-nums"
                                required
                            />
                            <x-input-error :messages="$errors->get('quantity')" class="mt-1.5" />
                        </div>

                        <div>
                            <x-input-label for="reason" :value="__('Motivo (opcional)')" />
                            <x-text-input
                                id="reason"
                                name="reason"
                                type="text"
                                class="mt-1.5"
                                :value="old('reason')"
                                placeholder="Ej. Compra proveedor, venta del día"
                            />
                            <x-input-error :messages="$errors->get('reason')" class="mt-1.5" />
                        </div>
                    </div>

                    <div class="rounded-2xl bg-[#F5F5F7] border border-black/[0.06] p-4 grid grid-cols-3 gap-3 text-center tabular-nums">
                        <div>
                            <p class="text-xs text-[#6E6E73]">Stock actual</p>
                            <p class="mt-1 text-lg font-semibold text-[#1D1D1F]" x-text="currentStock"></p>
                        </div>
                        <div>
                            <p class="text-xs text-[#6E6E73]">Variación</p>
                            <p class="mt-1 text-lg font-semibold text-[#0071E3]" x-text="deltaLabel"></p>
                        </div>
                        <div>
                            <p class="text-xs text-[#6E6E73]">Stock resultante</p>
                            <p
                                class="mt-1 text-lg font-semibold"
                                :class="resultingStock < 0 ? 'text-rose-600' : 'text-[#1D1D1F]'"
                                x-text="resultingStock"
                            ></p>
                        </div>
                    </div>

                    <p x-show="hasStockWarning" x-cloak class="text-xs text-rose-600">
                        <span x-show="type === 'salida'">La salida supera el stock disponible actual.</span>
                        <span x-show="type === 'ajuste'">El ajuste debe indicar un valor distinto al stock actual.</span>
                    </p>

                    <div class="pt-3 border-t border-black/[0.06] flex items-center justify-end gap-3">
                        <a href="{{ route('movements.index') }}" class="apple-press inline-flex items-center px-4 py-2.5 rounded-xl bg-white hover:bg-[#F5F5F7] border border-black/15 text-sm font-medium text-[#1D1D1F]">
                            Cancelar
                        </a>
                        <x-primary-button>
                            Guardar movimiento
                        </x-primary-button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
