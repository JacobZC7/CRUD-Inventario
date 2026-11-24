@props(['active'])

@php
$classes = ($active ?? false)
            ? 'apple-press inline-flex items-center px-3 py-1.5 rounded-lg bg-[#1D1D1F]/[0.06] text-sm font-medium text-[#1D1D1F] tracking-[-0.01em] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3]'
            : 'apple-press inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium text-[#6E6E73] hover:text-[#1D1D1F] hover:bg-[#1D1D1F]/[0.04] tracking-[-0.01em] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3]';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
