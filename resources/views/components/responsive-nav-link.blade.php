@props(['active'])

@php
$classes = ($active ?? false)
            ? 'apple-press flex items-center min-h-[44px] w-full px-3 py-2.5 rounded-xl bg-[#0071E3]/10 dark:bg-[#0A84FF]/15 text-start text-sm font-medium text-[#005BB5] dark:text-[#64B5F6] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] dark:focus-visible:ring-[#0A84FF]'
            : 'apple-press flex items-center min-h-[44px] w-full px-3 py-2.5 rounded-xl text-start text-sm font-medium text-[#6E6E73] dark:text-[#98989D] hover:text-[#1D1D1F] dark:hover:text-[#F5F5F7] hover:bg-[#1D1D1F]/[0.04] dark:hover:bg-white/[0.06] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] dark:focus-visible:ring-[#0A84FF]';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
