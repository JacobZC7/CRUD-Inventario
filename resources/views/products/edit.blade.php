<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold tracking-[-0.025em] text-[#1D1D1F] dark:text-[#F5F5F7]">
                    Editar producto
                </h1>
                <p class="mt-1 text-sm text-[#6E6E73] dark:text-[#98989D]">
                    {{ $product->name }} · {{ $product->sku }}
                </p>
            </div>

            <a href="{{ route('products.show', $product) }}" class="text-sm font-medium text-[#0071E3] dark:text-[#0A84FF] hover:underline">
                Ver detalle
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">
            <form
                method="POST"
                action="{{ route('products.update', $product) }}"
                x-data="{ submitting: false }"
                @submit="submitting = true"
                class="apple-surface p-6 sm:p-8 space-y-5"
            >
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="sku" :value="__('SKU / Código')" />
                        <x-text-input id="sku" name="sku" type="text" class="mt-1.5 font-mono uppercase" :value="old('sku', $product->sku)" required />
                        <x-input-error :messages="$errors->get('sku')" class="mt-1.5" />
                    </div>

                    <div>
                        <x-input-label for="category_id" :value="__('Categoría')" />
                        <select id="category_id" name="category_id" required class="mt-1.5 w-full min-h-[44px] sm:min-h-[40px] rounded-xl border border-black/15 dark:border-white/15 bg-white dark:bg-[#2C2C2E] py-2.5 px-3 text-sm text-[#1D1D1F] dark:text-[#F5F5F7] focus:border-[#0071E3] dark:focus:border-[#0A84FF] focus:outline-none focus:ring-2 focus:ring-[#0071E3]/20 dark:focus:ring-[#0A84FF]/25">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected((int) old('category_id', $product->category_id) === $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-1.5" />
                    </div>
                </div>

                <div>
                    <x-input-label for="name" :value="__('Nombre del producto')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1.5" :value="old('name', $product->name)" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                </div>

                <div>
                    <x-input-label for="description" :value="__('Descripción (opcional)')" />
                    <textarea id="description" name="description" rows="3" class="mt-1.5 w-full rounded-xl border border-black/15 dark:border-white/15 bg-white dark:bg-[#2C2C2E] px-3.5 py-2.5 text-sm text-[#1D1D1F] dark:text-[#F5F5F7] focus:border-[#0071E3] dark:focus:border-[#0A84FF] focus:outline-none focus:ring-2 focus:ring-[#0071E3]/20 dark:focus:ring-[#0A84FF]/25">{{ old('description', $product->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-1.5" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <x-input-label for="cost" :value="__('Costo ($)')" />
                        <x-text-input id="cost" name="cost" type="number" step="0.01" min="0" class="mt-1.5 tabular-nums" :value="old('cost', $product->cost)" required />
                        <x-input-error :messages="$errors->get('cost')" class="mt-1.5" />
                    </div>

                    <div>
                        <x-input-label for="price" :value="__('Precio venta ($)')" />
                        <x-text-input id="price" name="price" type="number" step="0.01" min="0" class="mt-1.5 tabular-nums" :value="old('price', $product->price)" required />
                        <x-input-error :messages="$errors->get('price')" class="mt-1.5" />
                    </div>

                    <div>
                        <x-input-label for="min_stock" :value="__('Stock mínimo')" />
                        <x-text-input id="min_stock" name="min_stock" type="number" min="0" class="mt-1.5 tabular-nums" :value="old('min_stock', $product->min_stock)" required />
                        <x-input-error :messages="$errors->get('min_stock')" class="mt-1.5" />
                    </div>
                </div>

                <p class="text-xs text-[#6E6E73] dark:text-[#98989D]">
                    Stock actual: <span class="font-semibold text-[#1D1D1F] dark:text-[#F5F5F7] tabular-nums">{{ $product->stock }}</span> unidades. Para modificar existencias manteniendo trazabilidad, registra un
                    <a href="{{ route('movements.create', ['product_id' => $product->id]) }}" class="text-[#0071E3] dark:text-[#0A84FF] hover:underline font-medium">movimiento de inventario</a>.
                </p>

                <div>
                    <label for="is_active" class="inline-flex items-center gap-2.5 min-h-[40px] cursor-pointer select-none">
                        <input id="is_active" name="is_active" type="checkbox" value="1" @checked(old('is_active', $product->is_active)) class="h-4 w-4 rounded-md border-black/20 dark:border-white/20 dark:bg-[#2C2C2E] text-[#0071E3] dark:text-[#0A84FF] focus:ring-[#0071E3]/25 dark:focus:ring-[#0A84FF]/30">
                        <span class="text-sm text-[#1D1D1F] dark:text-[#F5F5F7]">Producto activo en catálogo</span>
                    </label>
                </div>

                <div class="pt-3 border-t border-black/[0.06] dark:border-white/[0.08] flex items-center justify-end gap-3">
                    <a href="{{ route('products.show', $product) }}" class="apple-press inline-flex items-center justify-center min-h-[44px] sm:min-h-[40px] px-4 py-2.5 rounded-xl bg-white hover:bg-[#F5F5F7] dark:bg-[#2C2C2E] dark:hover:bg-[#3A3A3C] border border-black/15 dark:border-white/15 text-sm font-medium text-[#1D1D1F] dark:text-[#F5F5F7]">
                        Cancelar
                    </a>
                    <x-primary-button x-bind:disabled="submitting">
                        <span x-show="!submitting">Guardar cambios</span>
                        <span x-show="submitting" x-cloak>Guardando...</span>
                    </x-primary-button>
                </div>
            </form>

            <div class="apple-surface p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-sm font-semibold text-[#1D1D1F] dark:text-[#F5F5F7]">Eliminar producto</h2>
                    <p class="mt-0.5 text-xs text-[#6E6E73] dark:text-[#98989D]">
                        Esta acción elimina el producto y su historial de movimientos de forma permanente.
                    </p>
                </div>

                <x-danger-button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-product-deletion')">
                    Eliminar producto
                </x-danger-button>
            </div>

            <x-modal name="confirm-product-deletion" focusable maxWidth="md">
                <form method="POST" action="{{ route('products.destroy', $product) }}" x-data="{ deleting: false }" @submit="deleting = true" class="p-6 space-y-4">
                    @csrf
                    @method('DELETE')

                    <h2 class="text-lg font-semibold tracking-[-0.02em] text-[#1D1D1F] dark:text-[#F5F5F7]">
                        ¿Eliminar "{{ $product->name }}"?
                    </h2>
                    <p class="text-sm text-[#6E6E73] dark:text-[#98989D]">
                        Se eliminará el registro del catálogo junto con todo su historial de movimientos. Esta acción no se puede deshacer.
                    </p>

                    <div class="pt-2 flex justify-end gap-2.5">
                        <x-secondary-button x-on:click="$dispatch('close')">
                            Cancelar
                        </x-secondary-button>
                        <x-danger-button type="submit" x-bind:disabled="deleting">
                            <span x-show="!deleting">Sí, eliminar</span>
                            <span x-show="deleting" x-cloak>Eliminando...</span>
                        </x-danger-button>
                    </div>
                </form>
            </x-modal>
        </div>
    </div>
</x-app-layout>
