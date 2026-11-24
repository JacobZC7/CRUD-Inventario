<button {{ $attributes->merge(['type' => 'submit', 'class' => 'apple-press inline-flex items-center justify-center gap-2 min-h-[44px] sm:min-h-[40px] px-4 py-2.5 bg-rose-600 hover:bg-rose-500 active:bg-rose-700 border border-transparent rounded-xl font-medium text-sm text-white tracking-[-0.01em] focus:outline-none focus-visible:ring-2 focus-visible:ring-rose-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-[#1C1C1E] disabled:opacity-45 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>
