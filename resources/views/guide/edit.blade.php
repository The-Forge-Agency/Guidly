@extends('layouts.app')

@section('title', $guide->title . ' — Éditeur Guidly')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-6" x-data="guideEditor()">

    {{-- Top bar --}}
    <div class="flex items-center justify-between gap-2 mb-6">
        <a href="{{ route('my-guides') }}" class="text-sm text-ink-alt hover:text-ink transition-colors shrink-0">&larr; <span class="hidden sm:inline">Mes guides</span><span class="sm:hidden">Retour</span></a>
        <div class="flex items-center gap-2">
            <x-button href="{{ route('guide.preview', $guide->edit_token) }}" variant="ghost" size="sm">Aperçu</x-button>
            @if($guide->isPublished())
                <form action="{{ route('guide.unpublish', $guide->edit_token) }}" method="POST">
                    @csrf
                    <x-button type="submit" variant="outline" size="sm">Dépublier</x-button>
                </form>
            @else
                <form action="{{ route('guide.publish', $guide->edit_token) }}" method="POST">
                    @csrf
                    <x-button type="submit" size="sm">Publier</x-button>
                </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="bg-success/10 text-success rounded-xl p-4 mb-6 text-sm" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition>
            {{ session('success') }}
        </div>
    @endif

    {{-- Status badge --}}
    @if($guide->isPublished())
        <div class="inline-flex items-center gap-1.5 bg-success/10 text-success text-xs font-medium px-3 py-1 rounded-full mb-4">
            <span class="w-2 h-2 rounded-full bg-success"></span> Publié
        </div>
    @else
        <div class="inline-flex items-center gap-1.5 bg-bg-alt text-ink-alt text-xs font-medium px-3 py-1 rounded-full mb-4">
            <span class="w-2 h-2 rounded-full bg-ink-alt"></span> Brouillon
        </div>
    @endif

    {{-- Guide metadata --}}
    <x-card class="mb-6">
        <form action="{{ route('guide.update', $guide->edit_token) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <x-input name="title" label="Titre" :required="true" value="{{ old('title', $guide->title) }}" :error="$errors->first('title')" />
            <x-textarea name="description" label="Description" rows="2">{{ old('description', $guide->description) }}</x-textarea>
            <x-input name="creator_email" label="Email" type="email" value="{{ old('creator_email', $guide->creator_email) }}" />

            <div>
                <label class="block text-sm font-medium text-ink mb-1.5">Image de couverture</label>
                @if($guide->cover_image_path)
                    <div class="mb-2 rounded-xl overflow-hidden">
                        <img src="{{ $guide->getCoverImageUrl() }}" alt="Cover" class="w-full h-40 object-cover">
                    </div>
                @endif
                <input type="file" name="cover_image" accept="image/*" class="text-sm text-ink-alt file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-bg-alt file:text-ink file:font-medium file:cursor-pointer hover:file:bg-accent/10">
            </div>

            <x-button type="submit" size="sm">Enregistrer</x-button>
        </form>
    </x-card>

    {{-- Steps list --}}
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-display font-semibold text-xl">Étapes ({{ $guide->steps->count() }})</h2>
    </div>

    @if($guide->steps->isEmpty())
        <x-card>
            <x-empty-state
                title="Aucune étape"
                description="Ajoutez votre première étape pour commencer à construire votre guide."
                icon='<svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>'
            />
        </x-card>
    @else
        <div class="space-y-3" id="steps-list" x-ref="stepsList">
            @foreach($guide->steps as $step)
                <x-card class="group" :padding="false" data-step-id="{{ $step->id }}">
                    <div class="p-4 flex items-start gap-3">
                        <div class="cursor-grab text-ink-alt/40 hover:text-ink-alt mt-1 step-handle">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs text-ink-alt font-medium bg-bg-alt rounded-md px-2 py-0.5">{{ $step->order }}</span>
                                <h3 class="font-display font-semibold text-ink truncate">{{ $step->title }}</h3>
                            </div>
                            <div class="flex items-center gap-3 text-xs text-ink-alt">
                                @if($step->hasMedia())
                                    <span class="flex items-center gap-1">
                                        @if($step->media_type === \App\Enums\MediaType::Video)
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/></svg>
                                            Vidéo
                                        @else
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
                                            Photo
                                        @endif
                                    </span>
                                @endif
                                <span>{{ $step->action_type->label() }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            <button type="button" @click="editStep({{ $step->id }})" class="p-2 text-ink-alt hover:text-accent transition-colors rounded-lg hover:bg-bg-alt">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>
                            <form action="{{ route('step.destroy', [$guide->edit_token, $step]) }}" method="POST" onsubmit="return confirm('Supprimer cette étape ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-ink-alt hover:text-danger transition-colors rounded-lg hover:bg-bg-alt">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>
    @endif

    {{-- Add step button --}}
    <button type="button" @click="showAddStep = true" class="w-full mt-4 h-12 border-2 border-dashed border-bg-alt rounded-xl text-ink-alt hover:border-accent hover:text-accent transition-colors flex items-center justify-center gap-2 text-sm font-medium cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Ajouter une étape
    </button>

    {{-- Share link (if published) --}}
    @if($guide->isPublished())
        <x-card class="mt-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-display font-semibold text-sm mb-1">Lien public</h3>
                    <p class="text-xs text-ink-alt">{{ url('/g/' . $guide->slug) }}</p>
                </div>
                <x-button href="{{ route('guide.share', $guide->edit_token) }}" variant="outline" size="sm">Partager</x-button>
            </div>
        </x-card>
    @endif

    {{-- Add/Edit Step Modal --}}
    <div x-show="showAddStep || editingStepId" x-cloak
         class="fixed inset-0 z-50 flex items-end md:items-center justify-center"
         @keydown.escape.window="closeModal()">

        <div class="absolute inset-0 bg-ink/40" @click="closeModal()" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="relative bg-white w-full md:max-w-lg md:rounded-3xl rounded-t-3xl max-h-[85vh] overflow-y-auto shadow-card"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-full md:translate-y-0 md:scale-95 md:opacity-0"
             x-transition:enter-end="translate-y-0 md:scale-100 md:opacity-100">

            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="font-display font-bold text-xl" x-text="editingStepId ? 'Modifier l\'étape' : 'Nouvelle étape'"></h2>
                    <button @click="closeModal()" class="p-2 text-ink-alt hover:text-ink rounded-lg hover:bg-bg-alt transition-colors cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form :action="editingStepId ? '/guide/{{ $guide->edit_token }}/steps/' + editingStepId : '{{ route('step.store', $guide->edit_token) }}'"
                      method="POST" class="space-y-5">
                    @csrf
                    <template x-if="editingStepId">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <x-input name="title" label="Titre de l'étape" :required="true" placeholder="Ex: Parking" x-model="stepForm.title" />

                    {{-- TipTap Editor --}}
                    <div>
                        <label class="block text-sm font-medium text-ink mb-1.5">Contenu</label>
                        <div class="border-2 border-bg-alt rounded-lg overflow-hidden focus-within:border-accent focus-within:ring-2 focus-within:ring-accent/20 transition-colors">
                            <div class="flex items-center gap-1 px-3 py-2 border-b border-bg-alt bg-bg/50">
                                <button type="button" @click="toggleBold()" class="p-1.5 rounded hover:bg-bg-alt text-ink-alt hover:text-ink transition-colors" title="Gras">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6z"/><path d="M6 12h9a4 4 0 014 4 4 4 0 01-4 4H6z"/></svg>
                                </button>
                                <button type="button" @click="toggleItalic()" class="p-1.5 rounded hover:bg-bg-alt text-ink-alt hover:text-ink transition-colors" title="Italique">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/></svg>
                                </button>
                                <button type="button" @click="toggleHeading()" class="p-1.5 rounded hover:bg-bg-alt text-ink-alt hover:text-ink transition-colors text-xs font-bold" title="Titre">H2</button>
                                <button type="button" @click="toggleBulletList()" class="p-1.5 rounded hover:bg-bg-alt text-ink-alt hover:text-ink transition-colors" title="Liste">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><circle cx="3" cy="6" r="1" fill="currentColor"/><circle cx="3" cy="12" r="1" fill="currentColor"/><circle cx="3" cy="18" r="1" fill="currentColor"/></svg>
                                </button>
                            </div>
                            <div x-ref="editorContent" class="prose prose-sm max-w-none p-4 min-h-[120px] focus:outline-none [&_*]:outline-none" contenteditable="true"
                                 @input="stepForm.content_html = $refs.editorContent.innerHTML"></div>
                        </div>
                        <input type="hidden" name="content_html" :value="stepForm.content_html">
                    </div>

                    {{-- Media upload --}}
                    <div>
                        <label class="block text-sm font-medium text-ink mb-1.5">Média (photo ou vidéo)</label>
                        <div x-show="!stepForm.media_path" class="border-2 border-dashed border-bg-alt rounded-xl p-6 text-center hover:border-accent transition-colors">
                            <input type="file" accept="image/*,video/*" @change="handleMediaUpload($event)" class="hidden" x-ref="mediaInput">
                            <button type="button" @click="$refs.mediaInput.click()" class="text-ink-alt hover:text-accent transition-colors cursor-pointer">
                                <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-sm">Cliquez pour ajouter une photo ou vidéo</p>
                            </button>
                        </div>
                        <div x-show="stepForm.media_path" class="relative rounded-xl overflow-hidden">
                            <template x-if="stepForm.media_type === 'image'">
                                <img :src="stepForm.media_url" alt="Preview" class="w-full h-48 object-cover">
                            </template>
                            <template x-if="stepForm.media_type === 'video'">
                                <video :src="stepForm.media_url" class="w-full h-48 object-cover" controls></video>
                            </template>
                            <button type="button" @click="removeMedia()" class="absolute top-2 right-2 bg-ink/60 text-white rounded-full p-1.5 hover:bg-ink transition-colors cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <input type="hidden" name="media_path" :value="stepForm.media_path">
                        <input type="hidden" name="media_type" :value="stepForm.media_type">
                        <template x-if="editingStepId && !stepForm.media_path && stepForm.hadMedia">
                            <input type="hidden" name="remove_media" value="1">
                        </template>
                    </div>

                    {{-- Action type --}}
                    <div>
                        <label class="block text-sm font-medium text-ink mb-3">Action requise</label>
                        <input type="hidden" name="action_type" :value="stepForm.action_type">
                        <div class="grid grid-cols-2 gap-2">
                            <label @click="stepForm.action_type = 'acknowledge'" class="flex flex-col items-center gap-1.5 p-3 rounded-xl border-2 cursor-pointer transition-all text-center"
                                   :class="stepForm.action_type === 'acknowledge' ? 'border-accent bg-accent/5' : 'border-bg-alt hover:border-ink-alt/30'">
                                <svg class="w-5 h-5" :class="stepForm.action_type === 'acknowledge' ? 'text-accent' : 'text-ink-alt'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-xs font-medium" :class="stepForm.action_type === 'acknowledge' ? 'text-accent' : 'text-ink'">J'ai compris</span>
                            </label>
                            <label @click="stepForm.action_type = 'checkbox'" class="flex flex-col items-center gap-1.5 p-3 rounded-xl border-2 cursor-pointer transition-all text-center"
                                   :class="stepForm.action_type === 'checkbox' ? 'border-accent bg-accent/5' : 'border-bg-alt hover:border-ink-alt/30'">
                                <svg class="w-5 h-5" :class="stepForm.action_type === 'checkbox' ? 'text-accent' : 'text-ink-alt'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                                <span class="text-xs font-medium" :class="stepForm.action_type === 'checkbox' ? 'text-accent' : 'text-ink'">Cocher</span>
                            </label>
                            <label @click="stepForm.action_type = 'photo_upload'" class="flex flex-col items-center gap-1.5 p-3 rounded-xl border-2 cursor-pointer transition-all text-center"
                                   :class="stepForm.action_type === 'photo_upload' ? 'border-accent bg-accent/5' : 'border-bg-alt hover:border-ink-alt/30'">
                                <svg class="w-5 h-5" :class="stepForm.action_type === 'photo_upload' ? 'text-accent' : 'text-ink-alt'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z"/></svg>
                                <span class="text-xs font-medium" :class="stepForm.action_type === 'photo_upload' ? 'text-accent' : 'text-ink'">Photo</span>
                            </label>
                            <label @click="stepForm.action_type = 'none'" class="flex flex-col items-center gap-1.5 p-3 rounded-xl border-2 cursor-pointer transition-all text-center"
                                   :class="stepForm.action_type === 'none' ? 'border-accent bg-accent/5' : 'border-bg-alt hover:border-ink-alt/30'">
                                <svg class="w-5 h-5" :class="stepForm.action_type === 'none' ? 'text-accent' : 'text-ink-alt'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                <span class="text-xs font-medium" :class="stepForm.action_type === 'none' ? 'text-accent' : 'text-ink'">Aucune</span>
                            </label>
                        </div>
                    </div>

                    <div x-show="stepForm.action_type !== 'none'"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0">
                        <x-input name="action_label" label="Label du bouton (optionnel)" placeholder="J'ai compris" x-model="stepForm.action_label" />
                    </div>

                    {{-- Conditions --}}
                    <div>
                        <label class="block text-sm font-medium text-ink mb-3">Conditions d'affichage</label>
                        <input type="hidden" name="condition_type" :value="stepForm.condition_type">
                        <div class="space-y-2">
                            <label @click="stepForm.condition_type = 'none'" class="flex items-start gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all"
                                   :class="stepForm.condition_type === 'none' ? 'border-accent bg-accent/5' : 'border-bg-alt hover:border-ink-alt/30'">
                                <span class="mt-0.5 w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-colors"
                                      :class="stepForm.condition_type === 'none' ? 'border-accent' : 'border-ink-alt/30'">
                                    <span class="w-2.5 h-2.5 rounded-full bg-accent transition-transform" :class="stepForm.condition_type === 'none' ? 'scale-100' : 'scale-0'"></span>
                                </span>
                                <div>
                                    <span class="text-sm font-medium text-ink">Toujours visible</span>
                                    <p class="text-xs text-ink-alt mt-0.5">L'étape est accessible à tout moment.</p>
                                </div>
                            </label>

                            <label @click="stepForm.condition_type = 'time_range'" class="flex items-start gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all"
                                   :class="stepForm.condition_type === 'time_range' ? 'border-accent bg-accent/5' : 'border-bg-alt hover:border-ink-alt/30'">
                                <span class="mt-0.5 w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-colors"
                                      :class="stepForm.condition_type === 'time_range' ? 'border-accent' : 'border-ink-alt/30'">
                                    <span class="w-2.5 h-2.5 rounded-full bg-accent transition-transform" :class="stepForm.condition_type === 'time_range' ? 'scale-100' : 'scale-0'"></span>
                                </span>
                                <div class="flex-1">
                                    <span class="text-sm font-medium text-ink flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-ink-alt" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        Plage horaire
                                    </span>
                                    <p class="text-xs text-ink-alt mt-0.5">Visible uniquement entre deux heures de la journée.</p>
                                </div>
                            </label>

                            <label @click="stepForm.condition_type = 'date_range'" class="flex items-start gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all"
                                   :class="stepForm.condition_type === 'date_range' ? 'border-accent bg-accent/5' : 'border-bg-alt hover:border-ink-alt/30'">
                                <span class="mt-0.5 w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-colors"
                                      :class="stepForm.condition_type === 'date_range' ? 'border-accent' : 'border-ink-alt/30'">
                                    <span class="w-2.5 h-2.5 rounded-full bg-accent transition-transform" :class="stepForm.condition_type === 'date_range' ? 'scale-100' : 'scale-0'"></span>
                                </span>
                                <div class="flex-1">
                                    <span class="text-sm font-medium text-ink flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-ink-alt" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                        Plage de dates
                                    </span>
                                    <p class="text-xs text-ink-alt mt-0.5">Visible uniquement entre deux dates précises.</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div x-show="stepForm.condition_type === 'time_range'"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="grid grid-cols-2 gap-3 bg-bg/50 rounded-xl p-4 border border-bg-alt">
                        <x-input name="condition_start" label="De" type="time" x-model="stepForm.condition_start" />
                        <x-input name="condition_end" label="À" type="time" x-model="stepForm.condition_end" />
                    </div>

                    <div x-show="stepForm.condition_type === 'date_range'"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="grid grid-cols-2 gap-3 bg-bg/50 rounded-xl p-4 border border-bg-alt">
                        <x-input name="condition_start" label="Du" type="date" x-model="stepForm.condition_start" />
                        <x-input name="condition_end" label="Au" type="date" x-model="stepForm.condition_end" />
                    </div>

                    <div class="flex gap-3 pt-2">
                        <x-button type="submit" class="flex-1">Enregistrer</x-button>
                        <x-button type="button" variant="ghost" @click="closeModal()">Annuler</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
<script>
function guideEditor() {
    return {
        showAddStep: false,
        editingStepId: null,
        stepForm: {
            title: '',
            content_html: '',
            media_path: '',
            media_url: '',
            media_type: '',
            hadMedia: false,
            action_type: 'acknowledge',
            action_label: '',
            condition_type: 'none',
            condition_start: '',
            condition_end: '',
        },
        init() {
            this.$nextTick(() => {
                const list = document.getElementById('steps-list');
                if (list) {
                    new Sortable(list, {
                        handle: '.step-handle',
                        animation: 200,
                        onEnd: () => {
                            const order = Array.from(list.children)
                                .map(el => parseInt(el.dataset.stepId))
                                .filter(id => !isNaN(id));

                            fetch('{{ route("step.reorder", $guide->edit_token) }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({ order })
                            });
                        }
                    });
                }
            });
        },
        editStep(stepId) {
            const steps = @json($guide->steps);
            const step = steps.find(s => s.id === stepId);
            if (!step) return;

            this.editingStepId = stepId;
            this.stepForm = {
                title: step.title,
                content_html: step.content_html || '',
                media_path: step.media_path || '',
                media_url: step.media_path ? '/storage/' + step.media_path : '',
                media_type: step.media_type || '',
                hadMedia: !!step.media_path,
                action_type: step.action_type || 'acknowledge',
                action_label: step.action_label || '',
                condition_type: step.condition_type || 'none',
                condition_start: step.condition_start || '',
                condition_end: step.condition_end || '',
            };

            this.$nextTick(() => {
                if (this.$refs.editorContent) {
                    this.$refs.editorContent.innerHTML = step.content_html || '';
                }
            });
        },
        closeModal() {
            this.showAddStep = false;
            this.editingStepId = null;
            this.stepForm = {
                title: '', content_html: '', media_path: '', media_url: '', media_type: '', hadMedia: false,
                action_type: 'acknowledge', action_label: '', condition_type: 'none', condition_start: '', condition_end: ''
            };
            if (this.$refs.editorContent) {
                this.$refs.editorContent.innerHTML = '';
            }
        },
        async handleMediaUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('file', file);
            formData.append('guide_id', {{ $guide->id }});

            try {
                const res = await fetch('{{ route("api.upload-media") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: formData
                });
                const data = await res.json();
                if (data.success) {
                    this.stepForm.media_path = data.path;
                    this.stepForm.media_url = data.url;
                    this.stepForm.media_type = data.media_type;
                }
            } catch (e) {
                console.error('Upload failed', e);
            }
        },
        removeMedia() {
            this.stepForm.media_path = '';
            this.stepForm.media_url = '';
            this.stepForm.media_type = '';
        },
        toggleBold() { document.execCommand('bold'); },
        toggleItalic() { document.execCommand('italic'); },
        toggleHeading() {
            const sel = window.getSelection();
            if (sel.rangeCount) {
                document.execCommand('formatBlock', false, 'h2');
            }
        },
        toggleBulletList() { document.execCommand('insertUnorderedList'); },
    };
}
</script>
@endpush
@endsection
