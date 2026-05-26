<?php

use App\Http\Controllers\GuideController;
use App\Http\Controllers\ReaderController;
use App\Http\Controllers\StepController;
use Illuminate\Support\Facades\Route;

Route::get('/', [GuideController::class, 'index'])->name('home');
Route::get('/create', [GuideController::class, 'create'])->name('guide.create');
Route::post('/create', [GuideController::class, 'store'])->name('guide.store');
Route::get('/mes-guides', [GuideController::class, 'myGuides'])->name('my-guides');

Route::prefix('/guide/{edit_token}')->group(function () {
    Route::get('/', [GuideController::class, 'edit'])->name('guide.edit');
    Route::put('/', [GuideController::class, 'update'])->name('guide.update');
    Route::delete('/', [GuideController::class, 'destroy'])->name('guide.destroy');
    Route::post('/publish', [GuideController::class, 'publish'])->name('guide.publish');
    Route::post('/unpublish', [GuideController::class, 'unpublish'])->name('guide.unpublish');
    Route::get('/share', [GuideController::class, 'share'])->name('guide.share');
    Route::get('/stats', [GuideController::class, 'stats'])->name('guide.stats');
    Route::get('/preview', [GuideController::class, 'preview'])->name('guide.preview');

    Route::post('/steps', [StepController::class, 'store'])->name('step.store');
    Route::put('/steps/{step}', [StepController::class, 'update'])->name('step.update');
    Route::delete('/steps/{step}', [StepController::class, 'destroy'])->name('step.destroy');
    Route::post('/steps/reorder', [StepController::class, 'reorder'])->name('step.reorder');
});

Route::get('/g/{slug}', [ReaderController::class, 'show'])->name('reader.show');
Route::post('/g/{slug}/step/{step}/complete', [ReaderController::class, 'completeStep'])->name('reader.complete');
Route::post('/g/{slug}/step/{step}/upload', [ReaderController::class, 'uploadAction'])->name('reader.upload');

Route::post('/api/upload-media', [StepController::class, 'uploadMedia'])
    ->middleware('throttle:10,1')
    ->name('api.upload-media');
