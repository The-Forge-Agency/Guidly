@props([
    'padding' => true,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl shadow-card ' . ($padding ? 'p-6' : '')]) }}>
    {{ $slot }}
</div>
