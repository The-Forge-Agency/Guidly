@extends('layouts.app')

@section('title', 'Page introuvable — Guidly')

@section('content')
<div class="max-w-lg mx-auto px-4 py-20 text-center">
    <div class="w-20 h-20 rounded-2xl bg-bg-alt flex items-center justify-center mx-auto mb-6 text-4xl">🔍</div>
    <h1 class="font-display font-bold text-3xl text-ink mb-3">Page introuvable</h1>
    <p class="text-ink-alt mb-8">Ce guide n'existe pas ou a été supprimé.</p>
    <x-button href="{{ route('home') }}">Retour à l'accueil</x-button>
</div>
@endsection
