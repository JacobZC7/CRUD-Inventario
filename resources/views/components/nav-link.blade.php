@props(['active'])

@php
$classes = ($active ?? false)
            ? 'apple-press flex items-center gap-2.5 w-full px-3 py-2 rounded-xl bg-white dark:bg-white/[0.1] text-sm font-medium text-[#1D1D1F] dark:text-[#F5F5F7] shadow-[0_1px_2px_rgba(0,0,0,0.06)] dark:shadow-none tracking-[-0.01em] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] dark:focus-visible:ring-[#0A84FF]'
            : 'apple-press flex items-center gap-2.5 w-full px-3 py-2 rounded-xl text-sm font-medium text-[#6E6E73] dark:text-[#98989D] hover:text-[#1D1D1F] dark:hover:text-[#F5F5F7] hover:bg-black/[0.04] dark:hover:bg-white/[0.06] tracking-[-0.01em] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] dark:focus-visible:ring-[#0A84FF]';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
