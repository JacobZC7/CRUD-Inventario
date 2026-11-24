<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-[-0.025em] text-[#1D1D1F] dark:text-[#F5F5F7]">
                    Productos
                </h1>
                <p class="mt-1 text-sm text-[#6E6E73] dark:text-[#98989D]">
                    Catálogo de artículos, precios y existencias disponibles.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('products.export', request()->only(['search', 'category_id', 'stock_status'])) }}" class="apple-press inline-flex items-center justify-center min-h-[44px] sm:min-h-[40px] px-3.5 py-2 rounded-xl bg-white hover:bg-[#F5F5F7] dark:bg-[#2C2C2E] dark:hover:bg-[#3A3A3C] border border-black/15 dark:border-white/15 text-sm font-medium text-[#1D1D1F] dark:text-[#F5F5F7] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] dark:focus-visible:ring-[#0A84FF]">
                    Exportar CSV
                </a>

                <a href="{{ route('movements.create') }}" class="apple-press inline-flex items-center justify-center min-h-[44px] sm:min-h-[40px] px-4 py-2 rounded-xl bg-white hover:bg-[#F5F5F7] dark:bg-[#2C2C2E] dark:hover:bg-[#3A3A3C] border border-black/15 dark:border-white/15 text-sm font-medium text-[#1D1D1F] dark:text-[#F5F5F7] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] dark:focus-visible:ring-[#0A84FF]">
                    Registrar movimiento
                </a>

                @can('manage-products')
                    <a href="{{ route('products.create') }}" class="apple-press inline-flex items-center justify-center min-h-[44px] sm:min-h-[40px] px-4 py-2 rounded-xl bg-[#0071E3] hover:bg-[#0077ED] dark:bg-[#0A84FF] dark:hover:bg-[#3395FF] text-sm font-medium text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] dark:focus-visible:ring-[#0A84FF] focus-visible:ring-offset-2 dark:focus-visible:ring-offset-[#111113]">
                        Nuevo producto
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">
            @if (session('status'))
                <div class="rounded-2xl bg-emerald-50 dark:bg-emerald-500/15 border border-emerald-600/20 dark:border-emerald-400/25 px-4 py-3 text-sm text-emerald-800 dark:text-emerald-300">
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
                    <select name="category_id" aria-label="Filtrar por categoría" class="w-full min-h-[44px] sm:min-h-[40px] rounded-xl border border-black/15 dark:border-white/15 bg-white dark:bg-[#2C2C2E] py-2.5 px-3 text-sm text-[#1D1D1F] dark:text-[#F5F5F7] focus:border-[#0071E3] dark:focus:border-[#0A84FF] focus:outline-none focus:ring-2 focus:ring-[#0071E3]/20 dark:focus:ring-[#0A84FF]/25">
                        <option value="">Todas las categorías</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected($filters['category_id'] === $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full md:w-44">
                    <select name="stock_status" aria-label="Filtrar por estado de stock" class="w-full min-h-[44px] sm:min-h-[40px] rounded-xl border border-black/15 dark:border-white/15 bg-white dark:bg-[#2C2C2E] py-2.5 px-3 text-sm text-[#1D1D1F] dark:text-[#F5F5F7] focus:border-[#0071E3] dark:focus:border-[#0A84FF] focus:outline-none focus:ring-2 focus:ring-[#0071E3]/20 dark:focus:ring-[#0A84FF]/25">
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
                        <a href="{{ route('products.index') }}" class="apple-press inline-flex items-center justify-center min-h-[44px] sm:min-h-[40px] px-3.5 py-2.5 rounded-xl bg-white hover:bg-[#F5F5F7] dark:bg-[#2C2C2E] dark:hover:bg-[#3A3A3C] border border-black/15 dark:border-white/15 text-sm font-medium text-[#6E6E73] dark:text-[#98989D]">
                            Limpiar
                        </a>
                    @endif
                </div>
            </form>

            <div class="apple-surface overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-black/[0.06] dark:divide-white/[0.08]">
                        <thead>
                            <tr class="bg-[#F5F5F7]/70 dark:bg-white/[0.04]">
                                <th scope="col" class="px-5 py-3.5 text-left text-xs font-medium text-[#6E6E73] dark:text-[#98989D]">Producto</th>
                                <th scope="col" class="px-5 py-3.5 text-left text-xs font-medium text-[#6E6E73] dark:text-[#98989D]">Categoría</th>
                                <th scope="col" class="px-5 py-3.5 text-right text-xs font-medium text-[#6E6E73] dark:text-[#98989D]">Precio</th>
                                <th scope="col" class="px-5 py-3.5 text-right text-xs font-medium text-[#6E6E73] dark:text-[#98989D]">Stock</th>
                                <th scope="col" class="px-5 py-3.5 text-left text-xs font-medium text-[#6E6E73] dark:text-[#98989D]">Estado</th>
                                <th scope="col" class="px-5 py-3.5 text-right text-xs font-medium text-[#6E6E73] dark:text-[#98989D]">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/[0.06] dark:divide-white/[0.08] bg-white dark:bg-[#1C1C1E]">
                            @forelse ($products as $product)
                                <tr class="apple-row-hover">
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <a href="{{ route('products.show', $product) }}" class="text-sm font-medium text-[#1D1D1F] dark:text-[#F5F5F7] hover:text-[#0071E3] dark:hover:text-[#0A84FF]">
                                            {{ $product->name }}
                                        </a>
                                        <p class="text-xs font-mono text-[#6E6E73] dark:text-[#98989D]">
                                            {{ $product->sku }}
                                        </p>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-sm text-[#6E6E73] dark:text-[#98989D]">
                                        {{ $product->category->name }}
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-right text-sm font-medium text-[#1D1D1F] dark:text-[#F5F5F7] tabular-nums">
                                        ${{ number_format((float) $product->price, 2) }}
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-right text-sm font-semibold text-[#1D1D1F] dark:text-[#F5F5F7] tabular-nums">
                                        {{ $product->stock }}
                                        <span class="text-xs font-normal text-[#6E6E73] dark:text-[#98989D]">/ mín {{ $product->min_stock }}</span>
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $product->stockBadgeClasses() }}">
                                            {{ $product->stockStatusLabel() }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-2.5 whitespace-nowrap text-right text-sm">
                                        <div class="inline-flex items-center gap-1">
                                            <a href="{{ route('movements.create', ['product_id' => $product->id]) }}" class="apple-press inline-flex items-center min-h-[44px] sm:min-h-[34px] px-2.5 rounded-lg text-xs font-medium text-[#0071E3] dark:text-[#0A84FF] hover:bg-[#0071E3]/10 dark:hover:bg-[#0A84FF]/15">
                                                Movimiento
                                            </a>
                                            <a href="{{ route('products.show', $product) }}" class="apple-press inline-flex items-center min-h-[44px] sm:min-h-[34px] px-2.5 rounded-lg text-xs font-medium text-[#1D1D1F] dark:text-[#F5F5F7] hover:bg-[#1D1D1F]/[0.05] dark:hover:bg-white/[0.08]">
                                                Detalle
                                            </a>
                                            @can('manage-products')
                                                <a href="{{ route('products.edit', $product) }}" class="apple-press inline-flex items-center min-h-[44px] sm:min-h-[34px] px-2.5 rounded-lg text-xs font-medium text-[#6E6E73] dark:text-[#98989D] hover:text-[#1D1D1F] dark:hover:text-[#F5F5F7] hover:bg-[#1D1D1F]/[0.05] dark:hover:bg-white/[0.08]">
                                                    Editar
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-12 text-center">
                                        <p class="text-sm font-medium text-[#1D1D1F] dark:text-[#F5F5F7]">No hay productos que coincidan con tu búsqueda</p>
                                        <p class="mt-1 text-xs text-[#6E6E73] dark:text-[#98989D]">
                                            Ajusta los filtros o crea un nuevo producto para empezar.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($products->hasPages())
                    <div class="px-5 py-3.5 border-t border-black/[0.06] dark:border-white/[0.08] bg-[#F5F5F7]/40 dark:bg-white/[0.02] tabular-nums">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
