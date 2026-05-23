@extends('layouts.app')

@section('title', 'Mes guides — Guidly')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">

    <div class="flex items-center justify-between mb-8">
        <h1 class="font-display font-bold text-3xl text-ink">Mes guides</h1>
        @if($guides->count() < $maxGuides)
            <x-button href="{{ route('guide.create') }}" size="sm">Créer +</x-button>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-success/10 text-success rounded-xl p-4 mb-6 text-sm" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition>
            {{ session('success') }}
        </div>
    @endif

    @if($guides->isEmpty())
        <x-card>
            <x-empty-state
                title="Aucun guide"
                description="Vous n'avez pas encore créé de guide. Commencez maintenant, c'est gratuit !"
                icon='<svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>'
                action-label="Créer mon premier guide"
                :action-href="route('guide.create')"
            />
        </x-card>
    @else
        <p class="text-sm text-ink-alt mb-6">{{ $guides->count() }}/{{ $maxGuides }} guides utilisés</p>

        <div class="space-y-4">
            @foreach($guides as $guide)
                <x-card>
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <h2 class="font-display font-semibold text-lg text-ink truncate">{{ $guide->title }}</h2>
                                @if($guide->isPublished())
                                    <span class="inline-flex items-center gap-1 bg-success/10 text-success text-xs font-medium px-2 py-0.5 rounded-full shrink-0">
                                        <span class="w-1.5 h-1.5 rounded-full bg-success"></span>Publié
                                    </span>
                                @else
                                    <span class="inline-flex items-center bg-bg-alt text-ink-alt text-xs font-medium px-2 py-0.5 rounded-full shrink-0">Brouillon</span>
                                @endif
                            </div>
                            <p class="text-sm text-ink-alt">
                                {{ $guide->steps_count }} étape{{ $guide->steps_count > 1 ? 's' : '' }}
                                @if($guide->isPublished() && $guide->sessions_count > 0)
                                    · {{ $guide->sessions_count }} vue{{ $guide->sessions_count > 1 ? 's' : '' }}
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-button href="{{ route('guide.edit', $guide->edit_token) }}" variant="outline" size="sm">Éditer</x-button>
                            @if($guide->isPublished())
                                <x-button href="{{ route('guide.share', $guide->edit_token) }}" variant="ghost" size="sm">Partager</x-button>
                            @endif
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>
    @endif

</div>
@endsection
