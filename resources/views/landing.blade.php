@extends('layouts.app')

@section('title', 'Guidly — Des guides immersifs')

@section('content')

{{-- Hero --}}
<section class="relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-accent/[0.04] via-transparent to-success/[0.02] pointer-events-none"></div>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 pt-12 pb-10 sm:pt-16 sm:pb-16 md:pt-28 md:pb-24 relative">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 sm:gap-12 lg:gap-16 items-center">
            {{-- Left: copy --}}
            <div class="text-center lg:text-left">
                <p class="text-xs sm:text-sm font-medium text-accent tracking-wide uppercase mb-4 sm:mb-5" data-intro-step="1">Guides visuels interactifs</p>
                <h1 class="font-display font-bold text-3xl sm:text-[2.5rem] md:text-5xl lg:text-[3.5rem] text-ink leading-[1.12] sm:leading-[1.08] tracking-tight mb-5 sm:mb-6">
                    Expliquez une fois,<br> ne répétez plus jamais.
                </h1>
                <p class="text-base sm:text-lg text-ink-alt max-w-lg mx-auto lg:mx-0 mb-7 sm:mb-8 leading-relaxed">
                    Créez un guide visuel étape par étape, partagez un simple lien, et voyez exactement qui a lu quoi. Gratuit, sans inscription.
                </p>
                <div class="flex flex-col sm:flex-row items-center lg:items-start gap-3">
                    <x-button href="{{ route('guide.create') }}" size="lg" id="cta-create" class="w-full sm:w-auto">
                        Créer un guide
                    </x-button>
                    <a href="/g/bellecour"
                       class="inline-flex items-center justify-center h-13 px-6 text-ink font-display font-semibold rounded-xl border-2 border-bg-alt hover:border-accent/30 hover:text-accent transition-all duration-200 gap-2 w-full sm:w-auto" id="cta-demo">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/></svg>
                        Voir la démo
                    </a>
                </div>
                <button type="button" onclick="startDemoTour()" class="mt-4 text-sm text-ink-alt hover:text-accent transition-colors cursor-pointer flex items-center gap-1.5 mx-auto lg:mx-0" id="cta-tour">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/></svg>
                    Visite guidée de l'application
                </button>
            </div>

            {{-- Right: phone mockup --}}
            <div class="relative flex justify-center lg:justify-end" id="phone-mockup">
                <div class="relative max-w-[240px] sm:max-w-[280px] w-full">
                    <div class="rounded-[2rem] sm:rounded-[2.5rem] border-[5px] sm:border-[6px] border-ink/85 bg-bg shadow-2xl overflow-hidden">
                        <div class="bg-ink/85 h-6 flex items-center justify-center">
                            <div class="w-20 h-3 bg-ink/70 rounded-full"></div>
                        </div>
                        <div class="px-4 pt-4 pb-6">
                            {{-- Mini header --}}
                            <div class="flex items-center justify-center mb-4">
                                <span class="text-[9px] font-display font-bold text-ink/60 tracking-wider uppercase">Guidly</span>
                            </div>
                            {{-- Progress --}}
                            <div class="mb-1 flex items-center justify-between">
                                <span class="text-[9px] text-ink-alt">Étape 2 sur 7</span>
                                <span class="text-[9px] text-accent font-semibold">29%</span>
                            </div>
                            <div class="w-full h-1 bg-bg-alt rounded-full mb-4">
                                <div class="h-full bg-accent rounded-full" style="width: 29%"></div>
                            </div>
                            {{-- Step badge --}}
                            <div class="flex items-center gap-1.5 mb-2">
                                <span class="w-5 h-5 rounded-full bg-accent text-white flex items-center justify-center text-[8px] font-display font-bold">2</span>
                                <span class="text-[8px] text-ink-alt uppercase tracking-wider font-medium">Étape 2</span>
                            </div>
                            <h3 class="font-display font-bold text-sm text-ink mb-2 leading-tight">Entrer dans l'immeuble</h3>
                            <div class="rounded-lg overflow-hidden h-28 mb-3 shadow-sm">
                                <img src="{{ asset('images/demo/step-2-digicode.png') }}" alt="Digicode" class="w-full h-full object-cover">
                            </div>
                            <p class="text-[10px] text-ink/60 leading-relaxed mb-4">Le digicode est à droite de la porte. Tapez <strong class="text-ink">A1234</strong> puis appuyez sur la touche verte.</p>
                            {{-- CTA --}}
                            <div class="w-full h-9 bg-accent text-white rounded-lg flex items-center justify-center text-xs font-display font-semibold shadow-sm">
                                J'ai compris
                            </div>
                            {{-- Dots --}}
                            <div class="flex items-center justify-center gap-1 mt-3">
                                <div class="w-1.5 h-1.5 rounded-full bg-success"></div>
                                <div class="w-4 h-1.5 rounded-full bg-accent"></div>
                                <div class="w-1.5 h-1.5 rounded-full bg-ink-alt/15"></div>
                                <div class="w-1.5 h-1.5 rounded-full bg-ink-alt/15"></div>
                                <div class="w-1.5 h-1.5 rounded-full bg-ink-alt/15"></div>
                                <div class="w-1.5 h-1.5 rounded-full bg-ink-alt/15"></div>
                                <div class="w-1.5 h-1.5 rounded-full bg-ink-alt/15"></div>
                            </div>
                        </div>
                    </div>
                    {{-- Floating badge --}}
                    <div class="absolute -top-3 -right-3 bg-white rounded-xl shadow-card px-3 py-2 border border-bg-alt">
                        <div class="flex items-center gap-1.5">
                            <div class="w-5 h-5 rounded-full bg-success flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-[10px] font-medium text-ink">2/7 lues</span>
                        </div>
                    </div>
                    {{-- Decorative blurs --}}
                    <div class="absolute -z-10 -top-8 -right-8 w-36 h-36 bg-accent/6 rounded-full blur-2xl"></div>
                    <div class="absolute -z-10 -bottom-10 -left-10 w-44 h-44 bg-success/6 rounded-full blur-3xl"></div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Trust bar --}}
<section class="border-y border-bg-alt/60 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-3 sm:py-4 flex flex-wrap items-center justify-center gap-3 sm:gap-10 text-xs sm:text-sm text-ink-alt">
        <span class="flex items-center gap-2">
            <svg class="w-4 h-4 text-success" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            Gratuit
        </span>
        <span class="flex items-center gap-2">
            <svg class="w-4 h-4 text-success" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            Sans inscription
        </span>
        <span class="flex items-center gap-2">
            <svg class="w-4 h-4 text-success" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            3 guides inclus
        </span>
        <span class="flex items-center gap-2">
            <svg class="w-4 h-4 text-success" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            Mobile-first
        </span>
    </div>
</section>

{{-- How it works --}}
<section id="comment-ca-marche" class="py-12 sm:py-16 md:py-24 scroll-mt-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <p class="text-xs sm:text-sm font-medium text-accent tracking-wide uppercase text-center mb-3">Simple comme bonjour</p>
        <h2 class="font-display font-bold text-2xl sm:text-3xl md:text-4xl text-ink text-center mb-4">Trois étapes, zéro friction.</h2>
        <p class="text-sm sm:text-base text-ink-alt text-center max-w-xl mx-auto mb-10 sm:mb-16">De la création au suivi, tout se fait en quelques minutes. Pas de compte, pas de configuration compliquée.</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
            <div class="relative text-center group" id="step-create">
                <div class="w-20 h-20 rounded-2xl bg-white shadow-card flex items-center justify-center mx-auto mb-6 group-hover:shadow-lg transition-shadow duration-300 border border-bg-alt/50">
                    <svg class="w-9 h-9 text-accent" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                    </svg>
                </div>
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-accent text-white text-xs font-display font-bold mb-3">1</span>
                <h3 class="font-display font-bold text-lg text-ink mb-2">Créez</h3>
                <p class="text-sm text-ink-alt leading-relaxed">Ajoutez vos étapes avec photos, vidéos et instructions. L'éditeur riche formate votre contenu en quelques clics.</p>
            </div>

            <div class="relative text-center group" id="step-share">
                <div class="w-20 h-20 rounded-2xl bg-white shadow-card flex items-center justify-center mx-auto mb-6 group-hover:shadow-lg transition-shadow duration-300 border border-bg-alt/50">
                    <svg class="w-9 h-9 text-accent" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/>
                    </svg>
                </div>
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-accent text-white text-xs font-display font-bold mb-3">2</span>
                <h3 class="font-display font-bold text-lg text-ink mb-2">Partagez</h3>
                <p class="text-sm text-ink-alt leading-relaxed">Un lien unique ou un QR code. Votre destinataire ouvre et suit le guide sur son téléphone. Zéro inscription requise.</p>
            </div>

            <div class="relative text-center group" id="step-track">
                <div class="w-20 h-20 rounded-2xl bg-white shadow-card flex items-center justify-center mx-auto mb-6 group-hover:shadow-lg transition-shadow duration-300 border border-bg-alt/50">
                    <svg class="w-9 h-9 text-accent" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                    </svg>
                </div>
                <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-accent text-white text-xs font-display font-bold mb-3">3</span>
                <h3 class="font-display font-bold text-lg text-ink mb-2">Suivez</h3>
                <p class="text-sm text-ink-alt leading-relaxed">Voyez en temps réel qui a ouvert votre guide, quelles étapes ont été lues, et le taux de complétion.</p>
            </div>
        </div>
    </div>
</section>

{{-- Use cases --}}
<section class="py-12 sm:py-16 md:py-24 bg-white" id="use-cases">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <p class="text-xs sm:text-sm font-medium text-accent tracking-wide uppercase text-center mb-3">Cas d'usage</p>
        <h2 class="font-display font-bold text-2xl sm:text-3xl md:text-4xl text-ink text-center mb-4">Un seul outil, mille usages.</h2>
        <p class="text-sm sm:text-base text-ink-alt text-center max-w-xl mx-auto mb-10 sm:mb-16">Partout où vous devez transmettre des instructions claires et vérifier qu'elles ont été suivies.</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="group rounded-2xl border border-bg-alt bg-bg overflow-hidden hover:border-accent/20 hover:shadow-card transition-all duration-300">
                <div class="h-40 overflow-hidden">
                    <img src="{{ asset('images/demo/cover-bellecour.png') }}" alt="Place Bellecour" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="p-6">
                    <h3 class="font-display font-bold text-lg text-ink mb-3">Check-in location</h3>
                    <p class="text-sm text-ink-alt leading-relaxed mb-4">Code d'entrée, parking, WiFi, tri des déchets, check-out. Votre voyageur suit tout depuis son téléphone.</p>
                    <a href="/g/bellecour" class="text-xs text-accent font-medium hover:underline">Voir la démo</a>
                </div>
            </div>

            <div class="group rounded-2xl border border-bg-alt bg-bg overflow-hidden hover:border-accent/20 hover:shadow-card transition-all duration-300">
                <div class="h-40 overflow-hidden">
                    <img src="{{ asset('images/demo/step-3-couloir.png') }}" alt="Couloir d'immeuble" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="p-6">
                    <h3 class="font-display font-bold text-lg text-ink mb-3">Onboarding équipe</h3>
                    <p class="text-sm text-ink-alt leading-relaxed mb-4">Badge, poste de travail, outils internes, première réunion. Le nouveau suit son guide dès le premier jour.</p>
                    <p class="text-xs text-accent font-medium">Fini les infos perdues dans un e-mail.</p>
                </div>
            </div>

            <div class="group rounded-2xl border border-bg-alt bg-bg overflow-hidden hover:border-accent/20 hover:shadow-card transition-all duration-300">
                <div class="h-40 overflow-hidden">
                    <img src="{{ asset('images/demo/step-7-checkout.png') }}" alt="Lave-linge" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="p-6">
                    <h3 class="font-display font-bold text-lg text-ink mb-3">Tutoriels appareils</h3>
                    <p class="text-sm text-ink-alt leading-relaxed mb-4">Lave-linge, four, imprimante. Un QR code collé sur la machine, un guide visuel en quelques écrans.</p>
                    <p class="text-xs text-accent font-medium">Adieu les post-it illisibles.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Features highlight --}}
<section class="py-12 sm:py-16 md:py-24" id="features-section">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 sm:gap-12 md:gap-20 items-center">
            <div>
                <p class="text-xs sm:text-sm font-medium text-accent tracking-wide uppercase mb-3">Fonctionnalités</p>
                <h2 class="font-display font-bold text-2xl sm:text-3xl md:text-4xl text-ink mb-6 leading-tight">Tout ce qu'il faut pour guider, rien de superflu.</h2>
                <div class="space-y-5">
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-xl bg-accent/8 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-display font-semibold text-ink mb-1">Photos et vidéos</h3>
                            <p class="text-sm text-ink-alt leading-relaxed">Ajoutez des visuels à chaque étape pour rendre vos instructions limpides.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-xl bg-accent/8 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-display font-semibold text-ink mb-1">Preuves et validation</h3>
                            <p class="text-sm text-ink-alt leading-relaxed">Demandez une photo, une confirmation ou une coche pour chaque étape.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-xl bg-accent/8 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-display font-semibold text-ink mb-1">QR code intégré</h3>
                            <p class="text-sm text-ink-alt leading-relaxed">Générez un QR code à coller dans votre location, bureau ou sur un appareil.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-xl bg-accent/8 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                        </div>
                        <div>
                            <h3 class="font-display font-semibold text-ink mb-1">Optimisé mobile</h3>
                            <p class="text-sm text-ink-alt leading-relaxed">Le guide s'affiche parfaitement sur téléphone. Votre destinataire suit les étapes d'un simple geste.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats preview card --}}
            <div class="bg-white rounded-2xl shadow-card p-5 sm:p-8 border border-bg-alt/60" id="stats-preview">
                <h3 class="font-display font-bold text-lg text-ink mb-6">Tableau de bord</h3>
                <div class="grid grid-cols-3 gap-4 mb-8">
                    <div class="text-center p-3 rounded-xl bg-bg">
                        <div class="font-display font-bold text-2xl text-ink">24</div>
                        <div class="text-xs text-ink-alt mt-1">Lectures</div>
                    </div>
                    <div class="text-center p-3 rounded-xl bg-success/5">
                        <div class="font-display font-bold text-2xl text-success">18</div>
                        <div class="text-xs text-ink-alt mt-1">Complétés</div>
                    </div>
                    <div class="text-center p-3 rounded-xl bg-accent/5">
                        <div class="font-display font-bold text-2xl text-accent">75%</div>
                        <div class="text-xs text-ink-alt mt-1">Taux</div>
                    </div>
                </div>
                <div class="space-y-0">
                    <div class="flex items-center justify-between py-3 border-t border-bg-alt">
                        <div>
                            <div class="text-sm font-medium text-ink">Lecteur récent</div>
                            <div class="text-xs text-ink-alt">Il y a 2 heures</div>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs text-success font-medium">7/7</span>
                            <svg class="w-4 h-4 text-success" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                    </div>
                    <div class="flex items-center justify-between py-3 border-t border-bg-alt">
                        <div>
                            <div class="text-sm font-medium text-ink">Autre lecteur</div>
                            <div class="text-xs text-ink-alt">Hier</div>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs text-warning font-medium">5/7</span>
                            <svg class="w-4 h-4 text-warning" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Final CTA --}}
<section class="py-12 sm:py-16 md:py-24 bg-white">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 text-center">
        <h2 class="font-display font-bold text-2xl sm:text-3xl md:text-4xl text-ink mb-4 leading-tight">Prêt à créer votre premier guide ?</h2>
        <p class="text-ink-alt text-base sm:text-lg mb-8">Créez votre guide en quelques minutes. Gratuit, sans inscription, sans carte bancaire.</p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <x-button href="{{ route('guide.create') }}" size="lg" class="w-full sm:w-auto">
                Commencer maintenant
            </x-button>
            <a href="/g/bellecour"
               class="inline-flex items-center justify-center h-13 px-6 text-ink-alt hover:text-ink font-medium transition-colors gap-2 text-sm w-full sm:w-auto">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/></svg>
                Voir un exemple
            </a>
        </div>
    </div>
</section>

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
    .introjs-skipbutton:hover {
        background: #001639;
        color: white;
    }
    .introjs-tooltiptext {
        color: #6B7A8D;
        font-size: 0.875rem;
        line-height: 1.7;
        padding: 0;
    }
    .introjs-tooltipbuttons {
        border-top: 1px solid #EEF2F6;
        padding: 1rem 0 0;
        margin-top: 1rem;
    }
    .introjs-button {
        font-family: 'Red Hat Display', sans-serif;
        font-weight: 600;
        border-radius: 0.75rem;
        padding: 0.625rem 1.5rem;
        font-size: 0.875rem;
        text-shadow: none;
        border: none;
        transition: all 0.15s ease;
    }
    .introjs-button:focus {
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2);
    }
    .introjs-nextbutton {
        background-color: #2563EB;
        color: white;
    }
    .introjs-nextbutton:hover {
        background-color: #1D4ED8;
    }
    .introjs-prevbutton {
        background-color: #EEF2F6;
        color: #001639;
    }
    .introjs-prevbutton:hover {
        background-color: #dde3ea;
    }
    .introjs-helperLayer {
        border-radius: 1rem;
        box-shadow: 0 0 0 4000px rgba(0, 22, 57, 0.45);
    }
    .introjs-overlay {
        opacity: 0 !important;
    }
    .introjs-bullets {
        padding: 0.75rem 0 0;
    }
    .introjs-bullets ul li a {
        width: 8px;
        height: 8px;
        background: #dde3ea;
    }
    .introjs-bullets ul li a.active {
        width: 20px;
        border-radius: 4px;
        background: #2563EB;
    }
    .introjs-progressbar {
        background: #EEF2F6;
        height: 3px;
        border-radius: 2px;
    }
    .introjs-progressbar > div {
        background: #2563EB;
        border-radius: 2px;
    }
    .introjs-arrow {
        border: none;
    }
    .introjs-arrow.top {
        border-bottom-color: white;
    }
    .introjs-arrow.bottom {
        border-top-color: white;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/intro.js@7.2.0/intro.min.js"></script>
<script>
function startDemoTour() {
    introJs().setOptions({
        steps: [
            {
                title: 'Bienvenue sur Guidly',
                intro: 'Guidly vous permet de créer des guides visuels étape par étape. Voyons comment ça fonctionne.',
            },
            {
                element: '#phone-mockup',
                title: 'Le guide en action',
                intro: 'Voici à quoi ressemble un guide pour le destinataire. Chaque étape s\'affiche une par une, avec photo, instructions et bouton d\'action.',
                position: 'left',
            },
            {
                element: '#step-create',
                title: '1. Créez votre guide',
                intro: 'Ajoutez des étapes avec un éditeur riche : titre, texte formaté, photos ou vidéos. Configurez les actions requises (confirmation, photo, coche).',
                position: 'bottom',
            },
            {
                element: '#step-share',
                title: '2. Partagez le lien',
                intro: 'Chaque guide a un lien unique et un QR code. Envoyez-le par message, e-mail ou collez le QR code directement sur place.',
                position: 'bottom',
            },
            {
                element: '#step-track',
                title: '3. Suivez la lecture',
                intro: 'Voyez qui a ouvert votre guide, quelles étapes ont été lues, et le taux de complétion. En temps réel.',
                position: 'bottom',
            },
            {
                element: '#use-cases',
                title: 'Pour tous les contextes',
                intro: 'Check-in Airbnb, onboarding au bureau, tutoriel machine... Guidly s\'adapte à tous vos besoins de transmission d\'instructions.',
                position: 'top',
            },
            {
                element: '#stats-preview',
                title: 'Tableau de bord intégré',
                intro: 'Suivez les statistiques de chaque guide : nombre de lectures, taux de complétion, et historique des lecteurs.',
                position: 'left',
            },
            {
                element: '#cta-demo',
                title: 'Essayez la démo',
                intro: 'Cliquez sur « Voir la démo » pour tester un vrai guide en conditions réelles. Ou créez directement le vôtre — c\'est gratuit.',
                position: 'bottom',
            },
        ],
        showProgress: true,
        showBullets: true,
        exitOnOverlayClick: true,
        nextLabel: 'Suivant',
        prevLabel: 'Précédent',
        doneLabel: 'Voir la démo',
        skipLabel: '&times;',
    }).oncomplete(function() {
        window.location.href = '/g/bellecour';
    }).start();
}
</script>
@endpush
@endsection
