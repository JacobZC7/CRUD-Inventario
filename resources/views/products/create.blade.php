<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold tracking-[-0.025em] text-[#1D1D1F]">
                    Nuevo producto
                </h1>
                <p class="mt-1 text-sm text-[#6E6E73]">
                    Agrega un artículo al catálogo e indica su stock inicial.
                </p>
            </div>

            <a href="{{ route('products.index') }}" class="text-sm font-medium text-[#0071E3] hover:underline">
                Volver al catálogo
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('products.store') }}" class="apple-surface p-6 sm:p-8 space-y-5">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="sku" :value="__('SKU / Código')" />
                        <x-text-input id="sku" name="sku" type="text" class="mt-1.5 font-mono uppercase" :value="old('sku')" required autofocus placeholder="BEB-003" />
                        <x-input-error :messages="$errors->get('sku')" class="mt-1.5" />
                    </div>

                    <div>
                        <x-input-label for="category_id" :value="__('Categoría')" />
                        <select id="category_id" name="category_id" required class="mt-1.5 w-full rounded-xl border border-black/15 bg-white py-2.5 px-3 text-sm text-[#1D1D1F] focus:border-[#0071E3] focus:outline-none focus:ring-2 focus:ring-[#0071E3]/20">
                            <option value="">Selecciona una categoría</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected((int) old('category_id') === $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('category_id')" class="mt-1.5" />
                    </div>
                </div>

                <div>
                    <x-input-label for="name" :value="__('Nombre del producto')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1.5" :value="old('name')" required placeholder="Ej. Café Molido 250 g" />
                    <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                </div>

                <div>
                    <x-input-label for="description" :value="__('Descripción (opcional)')" />
                    <textarea id="description" name="description" rows="3" class="mt-1.5 w-full rounded-xl border border-black/15 bg-white px-3.5 py-2.5 text-sm text-[#1D1D1F] placeholder:text-[#86868B] focus:border-[#0071E3] focus:outline-none focus:ring-2 focus:ring-[#0071E3]/20" placeholder="Detalles de presentación o unidad de medida">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-1.5" />
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div>
                        <x-input-label for="cost" :value="__('Costo ($)')" />
                        <x-text-input id="cost" name="cost" type="number" step="0.01" min="0" class="mt-1.5 tabular-nums" :value="old('cost', '0.00')" required />
                        <x-input-error :messages="$errors->get('cost')" class="mt-1.5" />
                    </div>

                    <div>
                        <x-input-label for="price" :value="__('Precio venta ($)')" />
                        <x-text-input id="price" name="price" type="number" step="0.01" min="0" class="mt-1.5 tabular-nums" :value="old('price')" required />
                        <x-input-error :messages="$errors->get('price')" class="mt-1.5" />
                    </div>

                    <div>
                        <x-input-label for="stock" :value="__('Stock inicial')" />
                        <x-text-input id="stock" name="stock" type="number" min="0" class="mt-1.5 tabular-nums" :value="old('stock', 0)" required />
                        <x-input-error :messages="$errors->get('stock')" class="mt-1.5" />
                    </div>

                    <div>
                        <x-input-label for="min_stock" :value="__('Stock mínimo')" />
                        <x-text-input id="min_stock" name="min_stock" type="number" min="0" class="mt-1.5 tabular-nums" :value="old('min_stock', 5)" required />
                        <x-input-error :messages="$errors->get('min_stock')" class="mt-1.5" />
                    </div>
                </div>

                <div class="pt-1">
                    <label for="is_active" class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input id="is_active" name="is_active" type="checkbox" value="1" @checked(old('is_active', true)) class="rounded-md border-black/20 text-[#0071E3] focus:ring-[#0071E3]/25">
                        <span class="text-sm text-[#1D1D1F]">Producto activo en catálogo</span>
                    </label>
                </div>

                <div class="pt-3 border-t border-black/[0.06] flex items-center justify-end gap-3">
                    <a href="{{ route('products.index') }}" class="apple-press inline-flex items-center px-4 py-2.5 rounded-xl bg-white hover:bg-[#F5F5F7] border border-black/15 text-sm font-medium text-[#1D1D1F]">
                        Cancelar
                    </a>
                    <x-primary-button>
                        Guardar producto
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
