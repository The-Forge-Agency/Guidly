@props([
    'icon' => null,
    'title',
    'description' => null,
    'actionLabel' => null,
    'actionHref' => null,
])

<div class="flex flex-col items-center justify-center py-12 px-6 text-center">
    @if($icon)
        <div class="w-16 h-16 rounded-2xl bg-bg-alt flex items-center justify-center mb-4 text-ink-alt">
            {!! $icon !!}
        </div>
    @endif
    <h3 class="font-display font-semibold text-lg text-ink mb-1">{{ $title }}</h3>
    @if($description)
        <p class="text-sm text-ink-alt max-w-xs">{{ $description }}</p>
    @endif
    @if($actionLabel && $actionHref)
        <div class="mt-5">
            <x-button :href="$actionHref">{{ $actionLabel }}</x-button>
        </div>
    @endif
</div>
