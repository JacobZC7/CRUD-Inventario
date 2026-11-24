@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full px-3 py-2 rounded-xl bg-[#0071E3]/10 text-start text-sm font-medium text-[#005BB5] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] transition-[background-color,color] duration-150'
            : 'block w-full px-3 py-2 rounded-xl text-start text-sm font-medium text-[#6E6E73] hover:text-[#1D1D1F] hover:bg-[#1D1D1F]/[0.04] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] transition-[background-color,color] duration-150';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
