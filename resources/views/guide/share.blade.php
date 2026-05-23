@extends('layouts.app')

@section('title', 'Partager — ' . $guide->title)

@section('content')
<div class="max-w-lg mx-auto px-4 py-10">

    <a href="{{ route('guide.edit', $guide->edit_token) }}" class="inline-flex items-center text-sm text-ink-alt hover:text-ink transition-colors mb-6">
        &larr; Retour à l'éditeur
    </a>

    <h1 class="font-display font-bold text-3xl text-ink mb-2">Partagez votre guide</h1>
    <p class="text-ink-alt mb-8">{{ $guide->title }}</p>

    {{-- Lien public --}}
    <x-card class="mb-6">
        <h2 class="font-display font-semibold text-sm mb-3">Lien public</h2>
        <div x-data="{ copied: false }" class="flex items-center gap-2">
            <input type="text" readonly value="{{ url('/g/' . $guide->slug) }}"
                   class="flex-1 h-11 px-4 bg-bg-alt border-2 border-bg-alt rounded-lg text-sm text-ink font-mono">
            <button @click="navigator.clipboard.writeText('{{ url('/g/' . $guide->slug) }}'); copied = true; setTimeout(() => copied = false, 2000)"
                    class="h-11 px-4 bg-accent text-white rounded-lg font-medium text-sm hover:bg-accent-hover transition-colors cursor-pointer">
                <span x-show="!copied">Copier</span>
                <span x-show="copied" x-cloak>Copié !</span>
            </button>
        </div>
    </x-card>

    {{-- QR Code --}}
    <x-card>
        <h2 class="font-display font-semibold text-sm mb-3">QR Code</h2>
        <p class="text-xs text-ink-alt mb-4">Idéal pour afficher dans votre location, bureau ou sur une machine.</p>
        <div class="bg-white border-2 border-bg-alt rounded-xl p-8 flex items-center justify-center mb-4">
            <div class="w-48 h-48 bg-bg-alt rounded-lg flex items-center justify-center text-ink-alt text-sm" id="qr-placeholder">
                {{-- QR code will be generated here --}}
                <div x-data x-init="
                    const qr = new QRCode(document.getElementById('qr-container'), {
                        text: '{{ url('/g/' . $guide->slug) }}',
                        width: 192,
                        height: 192,
                        colorDark: '#001639',
                        colorLight: '#ffffff',
                        correctLevel: QRCode.CorrectLevel.M
                    });
                "></div>
                <div id="qr-container"></div>
            </div>
        </div>
        <div class="flex gap-3">
            <x-button href="{{ url('/g/' . $guide->slug) }}" variant="outline" size="sm" class="flex-1">Ouvrir le guide</x-button>
            <x-button href="{{ route('guide.stats', $guide->edit_token) }}" variant="ghost" size="sm">Statistiques</x-button>
        </div>
    </x-card>

</div>

@push('head')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
@endpush
@endsection
