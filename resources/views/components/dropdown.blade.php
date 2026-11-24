@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'p-1.5 bg-white'])

@php
$alignmentClasses = match ($align) {
    'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
    'top' => 'origin-top',
    default => 'ltr:origin-top-right rtl:origin-top-left end-0',
};

$width = match ($width) {
    '48' => 'w-52',
    default => $width,
};
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open"
            x-transition:enter="transition-[transform,opacity] ease-apple duration-150"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition-[transform,opacity] ease-apple duration-100"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="apple-motion-fade absolute z-50 mt-2 {{ $width }} rounded-2xl shadow-[0_12px_32px_-8px_rgba(0,0,0,0.16)] {{ $alignmentClasses }}"
            style="display: none;"
            @click="open = false">
        <div class="rounded-2xl ring-1 ring-black/[0.08] {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
