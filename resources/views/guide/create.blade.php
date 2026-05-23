@extends('layouts.app')

@section('title', 'Créer un guide — Guidly')

@section('content')
<div class="max-w-lg mx-auto px-4 py-10">

    <a href="{{ route('home') }}" class="inline-flex items-center text-sm text-ink-alt hover:text-ink transition-colors mb-6">
        &larr; Retour
    </a>

    <h1 class="font-display font-bold text-3xl text-ink mb-2" id="create-title">Créez votre guide</h1>
    <p class="text-ink-alt mb-8">Donnez un titre à votre guide. Vous pourrez ajouter les étapes ensuite.</p>

    @if(session('error'))
        <div class="bg-danger/10 text-danger rounded-xl p-4 mb-6 text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if($limitReached)
        <x-card>
            <div class="text-center py-6">
                <div class="w-14 h-14 rounded-full bg-danger/10 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-danger" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                </div>
                <h2 class="font-display font-semibold text-xl mb-2">Limite atteinte</h2>
                <p class="text-ink-alt mb-4">Vous avez déjà créé {{ $maxGuides }} guides (le maximum gratuit).</p>
                <x-button href="{{ route('my-guides') }}" variant="outline">Voir mes guides</x-button>
            </div>
        </x-card>
    @else
        <form action="{{ route('guide.store') }}" method="POST" class="space-y-5" id="create-form">
            @csrf

            <div id="field-title">
                <x-input
                    name="title"
                    label="Titre du guide"
                    placeholder="Ex: Check-in Appartement Lyon"
                    :error="$errors->first('title')"
                    :required="true"
                    value="{{ old('title') }}"
                />
            </div>

            <div id="field-description">
                <x-textarea
                    name="description"
                    label="Description (optionnel)"
                    placeholder="Quelques mots pour décrire ce guide..."
                    :error="$errors->first('description')"
                >{{ old('description') }}</x-textarea>
            </div>

            <div id="field-email">
                <x-input
                    name="creator_email"
                    label="Votre email (optionnel)"
                    type="email"
                    placeholder="mon@email.com"
                    :error="$errors->first('creator_email')"
                    value="{{ old('creator_email') }}"
                />
                <p class="text-xs text-ink-alt mt-1.5">Pour retrouver vos guides si vous changez d'appareil.</p>
            </div>

            <x-button type="submit" class="w-full" id="btn-submit">
                Créer mon guide &rarr;
            </x-button>

            <p class="text-xs text-ink-alt text-center">
                {{ $guideCount }}/{{ $maxGuides }} guides utilisés. Gratuit, sans inscription.
            </p>
        </form>

        <button type="button" onclick="startCreateTour()" class="mx-auto mt-6 text-sm text-ink-alt hover:text-accent transition-colors cursor-pointer flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/></svg>
            Comment ça marche ?
        </button>
    @endif

</div>

@push('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/minified/introjs.min.css">
<style>
    .introjs-tooltip {
        font-family: 'Inter', sans-serif;
        border-radius: 1.25rem;
        max-width: min(380px, calc(100vw - 2rem));
        padding: 1.25rem;
        box-shadow: 0 20px 60px rgba(0, 22, 57, 0.15);
        border: 1px solid #EEF2F6;
    }
    @media (min-width: 640px) {
        .introjs-tooltip { padding: 1.5rem; }
    }
    .introjs-tooltip-header {
        padding: 0 0 0.75rem 0;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
    }
    .introjs-tooltip-title {
        font-family: 'Red Hat Display', sans-serif;
        font-weight: 700;
        color: #001639;
        font-size: 1.125rem;
        line-height: 1.3;
        flex: 1;
    }
    .introjs-skipbutton {
        color: #6B7A8D;
        font-size: 1.25rem;
        line-height: 1;
        width: 2rem;
        height: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        background: #EEF2F6;
        flex-shrink: 0;
        margin: 0;
        padding: 0;
        position: static;
        transition: all 0.15s ease;
    }
    .introjs-skipbutton:hover { background: #001639; color: white; }
    .introjs-tooltiptext { color: #6B7A8D; font-size: 0.875rem; line-height: 1.7; padding: 0; }
    .introjs-tooltipbuttons { border-top: 1px solid #EEF2F6; padding: 1rem 0 0; margin-top: 1rem; }
    .introjs-button { font-family: 'Red Hat Display', sans-serif; font-weight: 600; border-radius: 0.75rem; padding: 0.625rem 1.5rem; font-size: 0.875rem; text-shadow: none; border: none; transition: all 0.15s ease; }
    .introjs-button:focus { box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2); }
    .introjs-nextbutton { background-color: #2563EB; color: white; }
    .introjs-nextbutton:hover { background-color: #1D4ED8; }
    .introjs-prevbutton { background-color: #EEF2F6; color: #001639; }
    .introjs-prevbutton:hover { background-color: #dde3ea; }
    .introjs-helperLayer { border-radius: 1rem; box-shadow: 0 0 0 4000px rgba(0, 22, 57, 0.45); }
    .introjs-overlay { opacity: 0 !important; }
    .introjs-bullets { padding: 0.75rem 0 0; }
    .introjs-bullets ul li a { width: 8px; height: 8px; background: #dde3ea; }
    .introjs-bullets ul li a.active { width: 20px; border-radius: 4px; background: #2563EB; }
    .introjs-progressbar { background: #EEF2F6; height: 3px; border-radius: 2px; }
    .introjs-progressbar > div { background: #2563EB; border-radius: 2px; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/intro.min.js"></script>
<script>
function startCreateTour() {
    introJs().setOptions({
        steps: [
            {
                element: '#create-title',
                title: 'Créer un guide',
                intro: 'Cette page vous permet de créer un nouveau guide visuel. Il vous suffit de remplir quelques informations de base.',
                position: 'bottom',
            },
            {
                element: '#field-title',
                title: 'Le titre',
                intro: 'Donnez un nom clair à votre guide. Par exemple : « Check-in Appartement Lyon » ou « Mode d\'emploi Lave-linge ».',
                position: 'bottom',
            },
            {
                element: '#field-description',
                title: 'La description',
                intro: 'Ajoutez une courte description pour donner du contexte à votre destinataire. Ce champ est optionnel.',
                position: 'bottom',
            },
            {
                element: '#field-email',
                title: 'Votre email',
                intro: 'Si vous renseignez votre email, vous pourrez retrouver vos guides même en changeant d\'appareil. Pas d\'inscription requise.',
                position: 'bottom',
            },
            {
                element: '#btn-submit',
                title: 'Et ensuite ?',
                intro: 'Après la création, vous accédez à l\'éditeur pour ajouter vos étapes : texte, photos, vidéos, actions requises. Puis vous publiez et partagez le lien.',
                position: 'top',
            },
        ],
        showProgress: true,
        showBullets: true,
        exitOnOverlayClick: true,
        nextLabel: 'Suivant',
        prevLabel: 'Précédent',
        doneLabel: 'Compris',
        skipLabel: '&times;',
    }).start();
}

@if(!$limitReached && $guideCount === 0)
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(startCreateTour, 800);
});
@endif
</script>
@endpush
@endsection
