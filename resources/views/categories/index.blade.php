<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h1 class="text-2xl font-semibold tracking-[-0.025em] text-[#1D1D1F] dark:text-[#F5F5F7]">
                    Categorías
                </h1>
                <p class="mt-1 text-sm text-[#6E6E73] dark:text-[#98989D]">
                    Organiza el catálogo por departamentos o familias de productos.
                </p>
            </div>

            <span class="inline-flex items-center self-start sm:self-auto px-3 py-1 rounded-full text-xs font-medium bg-white dark:bg-[#2C2C2E] border border-black/[0.08] dark:border-white/[0.1] text-[#6E6E73] dark:text-[#98989D] tabular-nums">
                {{ $categories->count() }} {{ $categories->count() === 1 ? 'categoría' : 'categorías' }}
            </span>
        </div>
    </x-slot>

    <div
        class="py-6"
        x-data="{
            deleteUrl: '',
            deleteName: '',
            deleteProductsCount: 0,
            deleting: false,
            openDeleteModal(url, name, productsCount) {
                this.deleteUrl = url;
                this.deleteName = name;
                this.deleteProductsCount = productsCount;
                this.deleting = false;
                $dispatch('open-modal', 'confirm-category-deletion');
            }
        }"
    >
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">
            @if (session('status'))
                <div class="rounded-2xl bg-emerald-50 dark:bg-emerald-500/15 border border-emerald-600/20 dark:border-emerald-400/25 px-4 py-3 text-sm text-emerald-800 dark:text-emerald-300">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->has('category'))
                <div class="rounded-2xl bg-rose-50 dark:bg-rose-500/15 border border-rose-600/20 dark:border-rose-400/25 px-4 py-3 text-sm text-rose-800 dark:text-rose-300">
                    {{ $errors->first('category') }}
                </div>
            @endif

            <section class="apple-surface p-6">
                <h2 class="text-base font-semibold tracking-[-0.015em] text-[#1D1D1F] dark:text-[#F5F5F7]">
                    Nueva categoría
                </h2>

                <form
                    method="POST"
                    action="{{ route('categories.store') }}"
                    x-data="{ submitting: false }"
                    @submit="submitting = true"
                    class="mt-4 grid grid-cols-1 md:grid-cols-12 gap-3 items-start"
                >
                    @csrf

                    <div class="md:col-span-4">
                        <x-input-label for="new_category_name" :value="__('Nombre')" />
                        <x-text-input
                            id="new_category_name"
                            name="name"
                            type="text"
                            class="mt-1.5"
                            :value="old('name')"
                            required
                            placeholder="Ej. Congelados"
                        />
                        <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                    </div>

                    <div class="md:col-span-6">
                        <x-input-label for="new_category_description" :value="__('Descripción (opcional)')" />
                        <x-text-input
                            id="new_category_description"
                            name="description"
                            type="text"
                            class="mt-1.5"
                            :value="old('description')"
                            placeholder="Breve detalle de los artículos incluidos"
                        />
                        <x-input-error :messages="$errors->get('description')" class="mt-1.5" />
                    </div>

                    <div class="md:col-span-2 md:pt-7">
                        <x-primary-button class="w-full justify-center" x-bind:disabled="submitting">
                            <span x-show="!submitting">Crear</span>
                            <span x-show="submitting" x-cloak>Creando...</span>
                        </x-primary-button>
                    </div>
                </form>
            </section>

            <section class="apple-surface overflow-hidden">
                @if ($categories->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <p class="text-sm font-medium text-[#1D1D1F] dark:text-[#F5F5F7]">No hay categorías creadas todavía</p>
                        <p class="mt-1 text-xs text-[#6E6E73] dark:text-[#98989D]">Usa el formulario superior para registrar tu primera categoría.</p>
                    </div>
                @else
                    <ul class="divide-y divide-black/[0.06] dark:divide-white/[0.08]">
                        @foreach ($categories as $category)
                            <li class="p-5 flex flex-col lg:flex-row lg:items-center gap-3">
                                <form
                                    method="POST"
                                    action="{{ route('categories.update', $category) }}"
                                    x-data="{ saving: false }"
                                    @submit="saving = true"
                                    class="flex-1 grid grid-cols-1 sm:grid-cols-12 gap-3 items-center"
                                >
                                    @csrf
                                    @method('PUT')

                                    <div class="sm:col-span-4">
                                        <label for="cat_name_{{ $category->id }}" class="sr-only">Nombre de categoría</label>
                                        <x-text-input
                                            id="cat_name_{{ $category->id }}"
                                            name="name"
                                            type="text"
                                            :value="$category->name"
                                            required
                                        />
                                    </div>

                                    <div class="sm:col-span-5">
                                        <label for="cat_desc_{{ $category->id }}" class="sr-only">Descripción de categoría</label>
                                        <x-text-input
                                            id="cat_desc_{{ $category->id }}"
                                            name="description"
                                            type="text"
                                            :value="$category->description"
                                            placeholder="Sin descripción"
                                        />
                                    </div>

                                    <div class="sm:col-span-3 flex items-center justify-between sm:justify-end gap-3">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[#F5F5F7] dark:bg-white/[0.08] text-[#6E6E73] dark:text-[#98989D] border border-black/[0.06] dark:border-white/[0.08] tabular-nums">
                                            {{ $category->products_count }} {{ $category->products_count === 1 ? 'producto' : 'productos' }}
                                        </span>

                                        <x-secondary-button type="submit" x-bind:disabled="saving">
                                            <span x-show="!saving">Guardar</span>
                                            <span x-show="saving" x-cloak>Guardando...</span>
                                        </x-secondary-button>
                                    </div>
                                </form>

                                <div class="shrink-0">
                                    <button
                                        type="button"
                                        @click="openDeleteModal(@js(route('categories.destroy', $category)), @js($category->name), {{ $category->products_count }})"
                                        class="apple-press inline-flex items-center justify-center min-h-[44px] sm:min-h-[40px] w-full lg:w-auto px-3.5 py-2 rounded-xl border border-rose-600/20 dark:border-rose-400/25 bg-rose-50 dark:bg-rose-500/15 hover:bg-rose-100 dark:hover:bg-rose-500/25 text-xs font-medium text-rose-700 dark:text-rose-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-500"
                                    >
                                        Eliminar
                                    </button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>

        <x-modal name="confirm-category-deletion" focusable maxWidth="md">
            <form method="POST" :action="deleteUrl" @submit="deleting = true" class="p-6 space-y-4">
                @csrf
                @method('DELETE')

                <h2 class="text-lg font-semibold tracking-[-0.02em] text-[#1D1D1F] dark:text-[#F5F5F7]">
                    ¿Eliminar "<span x-text="deleteName"></span>"?
                </h2>

                <p class="text-sm text-[#6E6E73] dark:text-[#98989D]" x-show="deleteProductsCount === 0">
                    Esta categoría no tiene productos asociados y se eliminará de forma permanente.
                </p>

                <p class="text-sm text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-500/15 border border-amber-600/20 dark:border-amber-400/25 rounded-xl px-3.5 py-2.5" x-show="deleteProductsCount > 0" x-cloak>
                    Esta categoría tiene <span class="font-semibold tabular-nums" x-text="deleteProductsCount"></span> producto(s) asociados. Reasígnalos antes de eliminarla o el sistema bloqueará la operación.
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
</x-app-layout>
