@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navegación de páginas">
        <div class="flex gap-2 items-center justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center min-h-[44px] px-4 py-2 text-sm font-medium text-[#86868B] bg-white border border-black/10 rounded-xl cursor-not-allowed">
                    Anterior
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="apple-press inline-flex items-center justify-center min-h-[44px] px-4 py-2 text-sm font-medium text-[#1D1D1F] bg-white hover:bg-[#F5F5F7] border border-black/15 rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3]">
                    Anterior
                </a>
            @endif

            <span class="text-xs font-medium text-[#6E6E73] tabular-nums">
                {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="apple-press inline-flex items-center justify-center min-h-[44px] px-4 py-2 text-sm font-medium text-[#1D1D1F] bg-white hover:bg-[#F5F5F7] border border-black/15 rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3]">
                    Siguiente
                </a>
            @else
                <span class="inline-flex items-center justify-center min-h-[44px] px-4 py-2 text-sm font-medium text-[#86868B] bg-white border border-black/10 rounded-xl cursor-not-allowed">
                    Siguiente
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:gap-4 sm:items-center sm:justify-between">
            <div>
                <p class="text-xs text-[#6E6E73] leading-5 tabular-nums">
                    Mostrando
                    @if ($paginator->firstItem())
                        <span class="font-medium text-[#1D1D1F]">{{ $paginator->firstItem() }}</span>
                        a
                        <span class="font-medium text-[#1D1D1F]">{{ $paginator->lastItem() }}</span>
                    @else
                        <span class="font-medium text-[#1D1D1F]">{{ $paginator->count() }}</span>
                    @endif
                    de
                    <span class="font-medium text-[#1D1D1F]">{{ $paginator->total() }}</span>
                    {{ $paginator->total() === 1 ? 'resultado' : 'resultados' }}
                </p>
            </div>

            <div>
                <span class="inline-flex items-center gap-1 tabular-nums">
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="Página anterior" class="inline-flex items-center justify-center h-9 w-9 text-[#86868B] bg-white border border-black/10 rounded-xl cursor-not-allowed">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Página anterior" class="apple-press inline-flex items-center justify-center h-9 w-9 text-[#1D1D1F] bg-white hover:bg-[#F5F5F7] border border-black/15 rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3]">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span aria-disabled="true" class="inline-flex items-center justify-center h-9 px-2.5 text-xs font-medium text-[#6E6E73]">
                                {{ $element }}
                            </span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page" class="inline-flex items-center justify-center h-9 min-w-[2.25rem] px-3 text-xs font-semibold text-white bg-[#0071E3] rounded-xl cursor-default">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" aria-label="Ir a la página {{ $page }}" class="apple-press inline-flex items-center justify-center h-9 min-w-[2.25rem] px-3 text-xs font-medium text-[#1D1D1F] bg-white hover:bg-[#F5F5F7] border border-black/15 rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3]">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Página siguiente" class="apple-press inline-flex items-center justify-center h-9 w-9 text-[#1D1D1F] bg-white hover:bg-[#F5F5F7] border border-black/15 rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3]">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="Página siguiente" class="inline-flex items-center justify-center h-9 w-9 text-[#86868B] bg-white border border-black/10 rounded-xl cursor-not-allowed">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
