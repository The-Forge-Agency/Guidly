@extends('layouts.app')

@section('title', 'Statistiques — ' . $guide->title)

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">

    <a href="{{ route('guide.edit', $guide->edit_token) }}" class="inline-flex items-center text-sm text-ink-alt hover:text-ink transition-colors mb-6">
        &larr; Retour à l'éditeur
    </a>

    <h1 class="font-display font-bold text-3xl text-ink mb-2">Statistiques</h1>
    <p class="text-ink-alt mb-8">{{ $guide->title }}</p>

    {{-- Stats cards --}}
    <div class="grid grid-cols-3 gap-2 sm:gap-4 mb-8">
        <x-card class="text-center">
            <div class="font-display font-bold text-2xl sm:text-3xl text-ink">{{ $totalSessions }}</div>
            <div class="text-xs text-ink-alt mt-1">Vues</div>
        </x-card>
        <x-card class="text-center">
            <div class="font-display font-bold text-2xl sm:text-3xl text-success">{{ $completedSessions }}</div>
            <div class="text-xs text-ink-alt mt-1">Terminés</div>
        </x-card>
        <x-card class="text-center">
            <div class="font-display font-bold text-2xl sm:text-3xl text-accent">{{ $completionRate }}%</div>
            <div class="text-xs text-ink-alt mt-1">Taux</div>
        </x-card>
    </div>

    {{-- Recent sessions --}}
    <h2 class="font-display font-semibold text-lg mb-4">Derniers lecteurs</h2>

    @if($sessions->isEmpty())
        <x-card>
            <x-empty-state
                title="Aucun lecteur"
                description="Partagez votre guide pour voir les statistiques ici."
                icon='<svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>'
                action-label="Partager"
                :action-href="route('guide.share', $guide->edit_token)"
            />
        </x-card>
    @else
        <div class="space-y-3">
            @foreach($sessions as $session)
                <x-card>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-ink">
                                {{ Str::mask($session->reader_ip, '*', 0, strlen($session->reader_ip) - 6) }}
                            </p>
                            <p class="text-xs text-ink-alt">{{ $session->started_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium {{ $session->isCompleted() ? 'text-success' : 'text-ink-alt' }}">
                                {{ $session->completions_count }}/{{ $guide->steps->count() }} étapes
                            </span>
                            @if($session->isCompleted())
                                <div class="w-6 h-6 rounded-full bg-success flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            @else
                                <div class="w-6 h-6 rounded-full bg-warning/20 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-warning" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            @endif
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>
    @endif

</div>
@endsection
