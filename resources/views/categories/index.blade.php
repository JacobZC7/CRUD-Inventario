<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h1 class="text-2xl font-semibold tracking-[-0.025em] text-[#1D1D1F]">
                    Categorías
                </h1>
                <p class="mt-1 text-sm text-[#6E6E73]">
                    Organiza el catálogo por departamentos o familias de productos.
                </p>
            </div>

            <span class="inline-flex items-center self-start sm:self-auto px-3 py-1 rounded-full text-xs font-medium bg-white border border-black/[0.08] text-[#6E6E73] tabular-nums">
                {{ $categories->count() }} {{ $categories->count() === 1 ? 'categoría' : 'categorías' }}
            </span>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">
            @if (session('status'))
                <div class="rounded-2xl bg-emerald-50 border border-emerald-600/20 px-4 py-3 text-sm text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->has('category'))
                <div class="rounded-2xl bg-rose-50 border border-rose-600/20 px-4 py-3 text-sm text-rose-800">
                    {{ $errors->first('category') }}
                </div>
            @endif

            <section class="apple-surface p-6">
                <h2 class="text-base font-semibold tracking-[-0.015em] text-[#1D1D1F]">
                    Nueva categoría
                </h2>

                <form method="POST" action="{{ route('categories.store') }}" class="mt-4 grid grid-cols-1 md:grid-cols-12 gap-3 items-start">
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
                        <x-primary-button class="w-full justify-center">
                            Crear
                        </x-primary-button>
                    </div>
                </form>
            </section>

            <section class="apple-surface overflow-hidden">
                @if ($categories->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <p class="text-sm font-medium text-[#1D1D1F]">No hay categorías creadas todavía</p>
                        <p class="mt-1 text-xs text-[#6E6E73]">Usa el formulario superior para registrar tu primera categoría.</p>
                    </div>
                @else
                    <ul class="divide-y divide-black/[0.06]">
                        @foreach ($categories as $category)
                            <li class="p-5 flex flex-col lg:flex-row lg:items-center gap-4">
                                <form method="POST" action="{{ route('categories.update', $category) }}" class="flex-1 grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
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
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-[#F5F5F7] text-[#6E6E73] border border-black/[0.06] tabular-nums">
                                            {{ $category->products_count }} {{ $category->products_count === 1 ? 'producto' : 'productos' }}
                                        </span>

                                        <x-secondary-button type="submit">
                                            Guardar
                                        </x-secondary-button>
                                    </div>
                                </form>

                                <form method="POST" action="{{ route('categories.destroy', $category) }}" class="shrink-0">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="apple-press inline-flex items-center px-3.5 py-2 rounded-xl border border-rose-600/20 bg-rose-50 hover:bg-rose-100 text-xs font-medium text-rose-700"
                                    >
                                        Eliminar
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
