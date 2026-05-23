@extends('layouts.app')

@section('title', $guide->title . ' — Guidly')
@section('og_title', $guide->title)
@section('og_description', $guide->description ?? 'Guide visuel étape par étape')

@section('custom_header')
<header class="w-full border-b border-bg-alt bg-white">
    <div class="max-w-lg mx-auto px-4 h-14 flex items-center justify-between">
        <div class="w-10"></div>
        <a href="{{ route('home') }}" class="flex items-center">
            <img src="{{ asset('images/logo-horizontal.png') }}" alt="Guidly" class="h-9 mix-blend-multiply">
        </a>
        <div class="w-10"></div>
    </div>
</header>
@endsection

@section('content')
<canvas id="confetti-canvas" class="fixed inset-0 z-[60] pointer-events-none" style="display:none;"></canvas>
<div class="max-w-lg mx-auto px-4 flex flex-col min-h-[calc(100vh-57px)]" x-data="readerGuide()" x-cloak
     x-init="$watch('isComplete', v => { if (v) setTimeout(launchConfetti, 100); })"

    @if($isPreview ?? false)
        <div class="bg-warning/10 text-warning rounded-xl p-3 mt-4 text-sm text-center font-medium">
            Mode aperçu — les actions ne sont pas enregistrées.
            <a href="{{ route('guide.edit', $guide->edit_token) }}" class="underline">Retour à l'éditeur</a>
        </div>
    @endif

    {{-- Progress --}}
    <div class="pt-4 pb-2">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs text-ink-alt font-medium">Étape <span x-text="currentStepIndex + 1"></span> sur <span x-text="totalSteps"></span></span>
            <span class="text-xs font-medium text-accent" x-text="Math.round(progressPercent) + '%'"></span>
        </div>
        <div class="w-full h-2 bg-bg-alt rounded-full overflow-hidden">
            <div class="h-full bg-accent rounded-full transition-all duration-500 ease-out" :style="'width: ' + progressPercent + '%'"></div>
        </div>
    </div>

    {{-- Step content --}}
    <div class="flex-1 py-6">
        @foreach($guide->steps as $index => $step)
        <div x-show="activeStep === {{ $step->id }}"
             x-transition:enter="transition-opacity ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
        >
            {{-- Step number badge --}}
            <div class="inline-flex items-center gap-2 mb-4">
                <span class="w-8 h-8 rounded-full bg-accent text-white flex items-center justify-center text-sm font-display font-bold">{{ $index + 1 }}</span>
                <span class="text-xs text-ink-alt font-medium uppercase tracking-wider">Étape {{ $index + 1 }}</span>
            </div>

            <h2 class="font-display font-bold text-2xl text-ink mb-5 leading-tight">{{ $step->title }}</h2>

            @if($step->hasMedia())
                <div class="rounded-2xl overflow-hidden mb-5 shadow-card">
                    @if($step->media_type === \App\Enums\MediaType::Video)
                        <video src="{{ $step->getMediaUrl() }}" controls class="w-full" playsinline></video>
                    @else
                        <img src="{{ $step->getMediaUrl() }}" alt="{{ $step->title }}" class="w-full object-cover max-h-72">
                    @endif
                </div>
            @endif

            @if($step->content_html)
                <div class="prose prose-sm max-w-none text-ink/80 [&_strong]:text-ink [&_a]:text-accent [&_p]:mb-3 leading-relaxed">
                    {!! $step->content_html !!}
                </div>
            @endif
        </div>
        @endforeach
    </div>

    {{-- Bottom area --}}
    <div class="pb-6 pt-2 space-y-3" x-show="!isComplete">
        {{-- CTA --}}
        <div x-show="!completedSteps.includes(activeStep)">
            <template x-if="currentStepAction === 'photo_upload'">
                <div>
                    <input type="file" accept="image/*" capture="environment" class="hidden" x-ref="photoInput" @change="handlePhotoUpload($event)">
                    <button @click="$refs.photoInput.click()"
                            class="w-full h-14 bg-accent text-white font-display font-semibold text-lg rounded-2xl shadow-card hover:bg-accent-hover transition-all duration-200 active:scale-[0.98] flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
                            :disabled="isLoading">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><circle cx="12" cy="13" r="3"/></svg>
                        <span x-text="currentStepLabel"></span>
                    </button>
                </div>
            </template>
            <template x-if="currentStepAction !== 'photo_upload'">
                <button @click="completeCurrentStep()"
                        class="w-full h-14 bg-accent text-white font-display font-semibold text-lg rounded-2xl shadow-card hover:bg-accent-hover transition-all duration-200 active:scale-[0.98] cursor-pointer disabled:opacity-50"
                        :disabled="isLoading">
                    <span x-text="currentStepLabel + ' →'"></span>
                </button>
            </template>
        </div>

        {{-- Navigation (completed steps) --}}
        <div x-show="completedSteps.includes(activeStep)" class="flex gap-3">
            <button @click="goToPrev()"
                    x-show="currentStepIndex > 0"
                    class="flex-1 h-14 bg-bg-alt text-ink font-display font-semibold rounded-2xl hover:bg-bg-alt/80 transition-all duration-200 cursor-pointer">
                ← Précédent
            </button>
            <button @click="goToNext()"
                    x-show="currentStepIndex < totalSteps - 1"
                    class="flex-1 h-14 bg-accent text-white font-display font-semibold rounded-2xl shadow-card hover:bg-accent-hover transition-all duration-200 cursor-pointer">
                Suivant →
            </button>
        </div>

        {{-- Step dots --}}
        <div class="flex items-center justify-center gap-2 pt-2">
            @foreach($guide->steps as $index => $step)
                <button @click="goToStep({{ $step->id }})"
                        class="h-2.5 rounded-full transition-all duration-300 cursor-pointer"
                        :class="activeStep === {{ $step->id }}
                            ? 'bg-accent w-7'
                            : (completedSteps.includes({{ $step->id }})
                                ? 'bg-success w-2.5'
                                : 'bg-ink-alt/20 w-2.5')"
                        aria-label="Étape {{ $index + 1 }}">
                </button>
            @endforeach
        </div>
    </div>

    {{-- Completion / Recap --}}
    <div x-show="isComplete"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="fixed inset-0 z-50 bg-bg overflow-y-auto">
        <div class="max-w-lg mx-auto px-4 py-8 relative z-[70]">
            {{-- Success header --}}
            <div class="text-center mb-8">
                <div class="w-20 h-20 rounded-full bg-success flex items-center justify-center mx-auto mb-5" style="animation: completeBounce 0.6s ease-out">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h2 class="font-display font-bold text-2xl sm:text-3xl text-ink mb-2">Guide terminé !</h2>
                <p class="text-ink-alt">Vous avez complété les <span x-text="totalSteps"></span> étapes.</p>
                <p class="text-ink-alt text-sm mt-1">{{ $guide->title }}</p>
            </div>

            {{-- Recap intro --}}
            <div class="bg-accent/5 border border-accent/15 rounded-2xl p-4 mb-6 text-center">
                <p class="text-sm text-ink font-medium">Revenez ici quand vous voulez</p>
                <p class="text-xs text-ink-alt mt-1">Retrouvez toutes les infos du guide ci-dessous.</p>
            </div>

            {{-- Steps accordion --}}
            <div class="space-y-3" x-data="{ openRecapStep: null }">
                @foreach($guide->steps as $index => $step)
                <div class="bg-white rounded-2xl shadow-card overflow-hidden">
                    <button @click="openRecapStep = openRecapStep === {{ $step->id }} ? null : {{ $step->id }}"
                            class="w-full flex items-center gap-3 p-4 text-left cursor-pointer hover:bg-bg-alt/30 transition-colors">
                        <span class="w-8 h-8 rounded-full bg-success/10 text-success flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span class="flex-1 font-display font-semibold text-ink text-sm">{{ $step->title }}</span>
                        <svg class="w-5 h-5 text-ink-alt transition-transform duration-200"
                             :class="openRecapStep === {{ $step->id }} && 'rotate-180'"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openRecapStep === {{ $step->id }}"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-cloak
                         class="border-t border-bg-alt">
                        @if($step->hasMedia())
                            <div class="overflow-hidden">
                                @if($step->media_type === \App\Enums\MediaType::Video)
                                    <video src="{{ $step->getMediaUrl() }}" controls class="w-full" playsinline></video>
                                @else
                                    <img src="{{ $step->getMediaUrl() }}" alt="{{ $step->title }}" class="w-full object-cover max-h-56">
                                @endif
                            </div>
                        @endif
                        @if($step->content_html)
                            <div class="p-4 prose prose-sm max-w-none text-ink/80 [&_strong]:text-ink [&_a]:text-accent [&_p]:mb-2 leading-relaxed text-sm">
                                {!! $step->content_html !!}
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Footer --}}
            <div class="mt-8 text-center space-y-4 pb-4">
                <p class="text-xs text-ink-alt">Merci et bon séjour !</p>
                <x-button href="{{ route('home') }}" variant="outline" class="inline-flex">Découvrir Guidly</x-button>
            </div>
        </div>
    </div>
</div>

@push('head')
<style>
    @keyframes completeBounce {
        0% { transform: scale(0); opacity: 0; }
        50% { transform: scale(1.15); }
        100% { transform: scale(1); opacity: 1; }
    }
    [x-cloak] { display: none !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
<script>
function launchConfetti() {
    const canvas = document.getElementById('confetti-canvas');
    if (!canvas) return;
    canvas.style.display = 'block';
    const confettiInstance = confetti.create(canvas, { resize: true, useWorker: true });

    const duration = 3000;
    const end = Date.now() + duration;

    (function frame() {
        confettiInstance({
            particleCount: 3,
            angle: 60,
            spread: 55,
            origin: { x: 0, y: 0.6 },
            colors: ['#2563EB', '#22C55E', '#F59E0B', '#EF4444', '#8B5CF6'],
        });
        confettiInstance({
            particleCount: 3,
            angle: 120,
            spread: 55,
            origin: { x: 1, y: 0.6 },
            colors: ['#2563EB', '#22C55E', '#F59E0B', '#EF4444', '#8B5CF6'],
        });

        if (Date.now() < end) requestAnimationFrame(frame);
    })();

    setTimeout(() => {
        confettiInstance({
            particleCount: 100,
            spread: 100,
            origin: { y: 0.5 },
            colors: ['#2563EB', '#22C55E', '#F59E0B'],
        });
    }, 400);

    setTimeout(() => { canvas.style.display = 'none'; }, 4000);
}

function readerGuide() {
    const steps = @json($guide->steps);
    const initialCompleted = @json($completedStepIds);
    const isPreview = {{ ($isPreview ?? false) ? 'true' : 'false' }};
    const slug = '{{ $guide->slug }}';
    const firstIncomplete = steps.find(s => !initialCompleted.includes(s.id));

    return {
        activeStep: firstIncomplete ? firstIncomplete.id : (steps.length ? steps[0].id : null),
        completedSteps: [...initialCompleted],
        totalSteps: steps.length,
        isComplete: initialCompleted.length >= steps.length && steps.length > 0,
        isLoading: false,

        get completedCount() { return this.completedSteps.length; },
        get progressPercent() { return this.totalSteps > 0 ? (this.completedCount / this.totalSteps) * 100 : 0; },
        get currentStepIndex() { return steps.findIndex(s => s.id === this.activeStep); },
        get currentStepAction() {
            const step = steps.find(s => s.id === this.activeStep);
            return step ? step.action_type : 'acknowledge';
        },
        get currentStepLabel() {
            const step = steps.find(s => s.id === this.activeStep);
            if (!step) return "J'ai compris";
            if (step.action_label) return step.action_label;
            if (step.action_type === 'photo_upload') return 'Envoyer une photo';
            if (step.action_type === 'checkbox') return "C'est fait";
            return "J'ai compris";
        },

        goToStep(stepId) { this.activeStep = stepId; },
        goToNext() {
            const idx = this.currentStepIndex;
            if (idx < steps.length - 1) this.activeStep = steps[idx + 1].id;
        },
        goToPrev() {
            const idx = this.currentStepIndex;
            if (idx > 0) this.activeStep = steps[idx - 1].id;
        },

        async completeCurrentStep() {
            if (!this.activeStep || isPreview || this.isLoading) return;
            this.isLoading = true;
            try {
                const res = await fetch(`/g/${slug}/step/${this.activeStep}/complete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const data = await res.json();
                if (data.success) {
                    this.completedSteps.push(this.activeStep);
                    if (data.is_guide_complete) {
                        setTimeout(() => { this.isComplete = true; }, 500);
                    } else if (data.next_step_id) {
                        setTimeout(() => { this.activeStep = data.next_step_id; }, 300);
                    }
                }
            } catch (e) {
                console.error('Error completing step', e);
            } finally {
                this.isLoading = false;
            }
        },

        async handlePhotoUpload(event) {
            const file = event.target.files[0];
            if (!file || isPreview) return;
            this.isLoading = true;
            const formData = new FormData();
            formData.append('photo', file);
            try {
                const res = await fetch(`/g/${slug}/step/${this.activeStep}/upload`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: formData
                });
                const data = await res.json();
                if (data.success) {
                    this.completedSteps.push(this.activeStep);
                    if (data.is_guide_complete) {
                        setTimeout(() => { this.isComplete = true; }, 500);
                    } else {
                        const nextStep = steps.find(s => !this.completedSteps.includes(s.id));
                        if (nextStep) setTimeout(() => { this.activeStep = nextStep.id; }, 300);
                    }
                }
            } catch (e) {
                console.error('Error uploading photo', e);
            } finally {
                this.isLoading = false;
            }
        },

        checkCondition(type, start, end) {
            const now = new Date();
            if (type === 'time_range' && start && end) {
                const [sh, sm] = start.split(':').map(Number);
                const [eh, em] = end.split(':').map(Number);
                const mins = now.getHours() * 60 + now.getMinutes();
                return mins >= (sh * 60 + sm) && mins <= (eh * 60 + em);
            }
            if (type === 'date_range' && start && end) {
                const today = now.toISOString().split('T')[0];
                return today >= start && today <= end;
            }
            return true;
        }
    };
}
</script>
@endpush
@endsection
