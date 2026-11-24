@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium text-[#1D1D1F] dark:text-[#F5F5F7] tracking-[-0.01em]']) }}>
    {{ $value ?? $slot }}
</label>
