@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium text-[#1D1D1F] tracking-[-0.01em]']) }}>
    {{ $value ?? $slot }}
</label>
