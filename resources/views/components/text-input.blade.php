@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full rounded-xl border border-black/15 bg-white px-3.5 py-2.5 text-sm text-[#1D1D1F] placeholder:text-[#86868B] transition-[border-color,box-shadow] duration-150 focus:border-[#0071E3] focus:outline-none focus:ring-2 focus:ring-[#0071E3]/20 disabled:bg-[#F5F5F7] disabled:text-[#86868B]']) }}>
