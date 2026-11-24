<button {{ $attributes->merge(['type' => 'button', 'class' => 'apple-press inline-flex items-center justify-center gap-2 min-h-[44px] sm:min-h-[40px] px-4 py-2.5 bg-white hover:bg-[#F5F5F7] border border-black/15 rounded-xl font-medium text-sm text-[#1D1D1F] tracking-[-0.01em] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] focus-visible:ring-offset-2 disabled:opacity-45 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>
