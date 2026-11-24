<button {{ $attributes->merge(['type' => 'submit', 'class' => 'apple-press inline-flex items-center justify-center gap-2 min-h-[44px] sm:min-h-[40px] px-4 py-2.5 bg-[#0071E3] hover:bg-[#0077ED] active:bg-[#0066CC] border border-transparent rounded-xl font-medium text-sm text-white tracking-[-0.01em] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0071E3] focus-visible:ring-offset-2 disabled:opacity-45 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>
