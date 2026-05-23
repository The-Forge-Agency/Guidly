@props([
    'name',
    'label' => null,
    'options' => [],
    'error' => null,
    'required' => false,
    'selected' => null,
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
    <select
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'w-full h-11 px-4 bg-white border-2 rounded-lg text-ink transition-colors duration-200 focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 appearance-none cursor-pointer ' . ($error ? 'border-danger' : 'border-bg-alt')]) }}
    >
        @foreach($options as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    @if($error)
        <p class="mt-1 text-xs text-danger">{{ $error }}</p>
    @endif
</div>
