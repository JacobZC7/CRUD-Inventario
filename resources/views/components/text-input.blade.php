@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full rounded-xl border border-black/15 dark:border-white/15 bg-white dark:bg-[#2C2C2E] px-3.5 py-2.5 text-sm text-[#1D1D1F] dark:text-[#F5F5F7] placeholder:text-[#86868B] dark:placeholder:text-[#98989D] transition-[border-color,box-shadow] duration-150 focus:border-[#0071E3] dark:focus:border-[#0A84FF] focus:outline-none focus:ring-2 focus:ring-[#0071E3]/20 dark:focus:ring-[#0A84FF]/25 disabled:bg-[#F5F5F7] dark:disabled:bg-[#1C1C1E] disabled:text-[#86868B] dark:disabled:text-[#6E6E73]']) }}>
