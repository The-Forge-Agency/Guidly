@props([
    'name',
    'label' => null,
    'type' => 'text',
    'error' => null,
    'placeholder' => '',
    'required' => false,
])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-ink mb-1.5">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'w-full h-11 px-4 bg-white border-2 rounded-lg text-ink placeholder-ink-alt/50 transition-colors duration-200 focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 ' . ($error ? 'border-danger' : 'border-bg-alt')]) }}
    >
    @if($error)
        <p class="mt-1 text-xs text-danger">{{ $error }}</p>
    @endif
</div>
