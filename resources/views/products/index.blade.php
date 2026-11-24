<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-[-0.025em] text-[#1D1D1F]">
                    Productos
                </h1>
                <p class="mt-1 text-sm text-[#6E6E73]">
                    Catálogo de artículos, precios y existencias disponibles.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('products.export', request()->only(['search', 'category_id', 'stock_status'])) }}" class="apple-press inline-flex items-center px-3.5 py-2 rounded-xl bg-white hover:bg-[#F5F5F7] border border-black/15 text-sm font-medium text-[#1D1D1F]">
                    Exportar CSV
                </a>

                <a href="{{ route('movements.create') }}" class="apple-press inline-flex items-center px-4 py-2 rounded-xl bg-white hover:bg-[#F5F5F7] border border-black/15 text-sm font-medium text-[#1D1D1F]">
                    Registrar movimiento
                </a>

                @can('manage-products')
                    <a href="{{ route('products.create') }}" class="apple-press inline-flex items-center px-4 py-2 rounded-xl bg-[#0071E3] hover:bg-[#0077ED] text-sm font-medium text-white">
                        Nuevo producto
                    </a>
                @endcan
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

            <form method="GET" action="{{ route('products.index') }}" class="apple-surface p-4 flex flex-col md:flex-row items-stretch md:items-center gap-3">
                <div class="flex-1">
                    <x-text-input
                        type="search"
                        name="search"
                        :value="$filters['search']"
                        placeholder="Buscar por nombre o SKU..."
                    />
                </div>

                <div class="w-full md:w-52">
                    <select name="category_id" aria-label="Filtrar por categoría" class="w-full rounded-xl border border-black/15 bg-white py-2.5 px-3 text-sm text-[#1D1D1F] focus:border-[#0071E3] focus:outline-none focus:ring-2 focus:ring-[#0071E3]/20">
                        <option value="">Todas las categorías</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected($filters['category_id'] === $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full md:w-44">
                    <select name="stock_status" aria-label="Filtrar por estado de stock" class="w-full rounded-xl border border-black/15 bg-white py-2.5 px-3 text-sm text-[#1D1D1F] focus:border-[#0071E3] focus:outline-none focus:ring-2 focus:ring-[#0071E3]/20">
                        <option value="">Todo el stock</option>
                        <option value="ok" @selected($filters['stock_status'] === 'ok')>Disponible</option>
                        <option value="low" @selected($filters['stock_status'] === 'low')>Stock bajo</option>
                        <option value="out" @selected($filters['stock_status'] === 'out')>Agotado</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <x-primary-button type="submit">
                        Filtrar
                    </x-primary-button>

                    @if ($filters['search'] !== '' || $filters['category_id'] > 0 || $filters['stock_status'] !== '')
                        <a href="{{ route('products.index') }}" class="apple-press inline-flex items-center px-3.5 py-2.5 rounded-xl bg-white hover:bg-[#F5F5F7] border border-black/15 text-sm font-medium text-[#6E6E73]">
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
                                <th scope="col" class="px-5 py-3.5 text-left text-xs font-medium text-[#6E6E73]">Producto</th>
                                <th scope="col" class="px-5 py-3.5 text-left text-xs font-medium text-[#6E6E73]">Categoría</th>
                                <th scope="col" class="px-5 py-3.5 text-right text-xs font-medium text-[#6E6E73]">Precio</th>
                                <th scope="col" class="px-5 py-3.5 text-right text-xs font-medium text-[#6E6E73]">Stock</th>
                                <th scope="col" class="px-5 py-3.5 text-left text-xs font-medium text-[#6E6E73]">Estado</th>
                                <th scope="col" class="px-5 py-3.5 text-right text-xs font-medium text-[#6E6E73]">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/[0.06] bg-white">
                            @forelse ($products as $product)
                                <tr class="apple-row-hover">
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <a href="{{ route('products.show', $product) }}" class="text-sm font-medium text-[#1D1D1F] hover:text-[#0071E3]">
                                            {{ $product->name }}
                                        </a>
                                        <p class="text-xs font-mono text-[#6E6E73]">
                                            {{ $product->sku }}
                                        </p>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-sm text-[#6E6E73]">
                                        {{ $product->category->name }}
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-right text-sm font-medium text-[#1D1D1F] tabular-nums">
                                        ${{ number_format((float) $product->price, 2) }}
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-right text-sm font-semibold text-[#1D1D1F] tabular-nums">
                                        {{ $product->stock }}
                                        <span class="text-xs font-normal text-[#6E6E73]">/ mín {{ $product->min_stock }}</span>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $product->stockBadgeClasses() }}">
                                            {{ $product->stockStatusLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-right text-sm">
                                        <div class="inline-flex items-center gap-3">
                                            <a href="{{ route('movements.create', ['product_id' => $product->id]) }}" class="text-xs font-medium text-[#0071E3] hover:underline">
                                                Movimiento
                                            </a>
                                            <a href="{{ route('products.show', $product) }}" class="text-xs font-medium text-[#1D1D1F] hover:text-[#0071E3]">
                                                Detalle
                                            </a>
                                            @can('manage-products')
                                                <a href="{{ route('products.edit', $product) }}" class="text-xs font-medium text-[#6E6E73] hover:text-[#1D1D1F]">
                                                    Editar
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-12 text-center">
                                        <p class="text-sm font-medium text-[#1D1D1F]">No hay productos que coincidan con tu búsqueda</p>
                                        <p class="mt-1 text-xs text-[#6E6E73]">
                                            Ajusta los filtros o crea un nuevo producto para empezar.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($products->hasPages())
                    <div class="px-5 py-3.5 border-t border-black/[0.06] bg-[#F5F5F7]/40 tabular-nums">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
