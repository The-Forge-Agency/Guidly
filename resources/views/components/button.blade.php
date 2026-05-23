@props([
    'variant' => 'primary',
    'type' => 'button',
    'href' => null,
    'size' => 'md',
])

@php
    $base = 'inline-flex items-center justify-center font-display font-semibold transition-all duration-200 ease-out active:scale-[0.97] cursor-pointer';

    $sizes = [
        'sm' => 'h-9 px-4 text-sm rounded-lg',
        'md' => 'h-11 px-6 text-base rounded-xl',
        'lg' => 'h-13 px-8 text-lg rounded-xl',
    ];

    $variants = [
        'primary' => 'bg-accent text-white hover:bg-accent-hover shadow-sm hover:shadow-card',
        'outline' => 'border-2 border-accent text-accent hover:bg-accent hover:text-white',
        'ghost' => 'text-ink-alt hover:text-ink hover:bg-bg-alt',
        'danger' => 'bg-danger text-white hover:bg-red-600',
    ];

    $classes = $base . ' ' . ($sizes[$size] ?? $sizes['md']) . ' ' . ($variants[$variant] ?? $variants['primary']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
