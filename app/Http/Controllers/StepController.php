<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use App\Models\Step;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StepController extends Controller
{
    public function store(Request $request, string $edit_token): RedirectResponse
    {
        $guide = Guide::where('edit_token', $edit_token)->firstOrFail();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content_html' => 'nullable|string',
            'action_type' => 'nullable|string|in:acknowledge,photo_upload,checkbox,none',
            'action_label' => 'nullable|string|max:255',
            'condition_type' => 'nullable|string|in:none,time_range,date_range',
            'condition_start' => 'nullable|string',
            'condition_end' => 'nullable|string',
        ]);

        $maxOrder = $guide->steps()->max('order') ?? 0;

        $step = $guide->steps()->create(array_merge($validated, [
            'order' => $maxOrder + 1,
            'content_html' => $this->sanitizeHtml($validated['content_html'] ?? ''),
        ]));

        if ($request->filled('media_path')) {
            $step->update([
                'media_path' => $request->input('media_path'),
                'media_type' => $request->input('media_type'),
            ]);
        }

        return redirect()->route('guide.edit', $edit_token)
            ->with('success', 'Étape ajoutée.');
    }

    public function update(Request $request, string $edit_token, Step $step): RedirectResponse
    {
        $guide = Guide::where('edit_token', $edit_token)->firstOrFail();
        abort_if($step->guide_id !== $guide->id, 404);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content_html' => 'nullable|string',
            'action_type' => 'nullable|string|in:acknowledge,photo_upload,checkbox,none',
            'action_label' => 'nullable|string|max:255',
            'condition_type' => 'nullable|string|in:none,time_range,date_range',
            'condition_start' => 'nullable|string',
            'condition_end' => 'nullable|string',
            'media_path' => 'nullable|string',
            'media_type' => 'nullable|string|in:image,video',
            'remove_media' => 'nullable|boolean',
        ]);

        $updateData = [
            'title' => $validated['title'],
            'content_html' => $this->sanitizeHtml($validated['content_html'] ?? ''),
            'action_type' => $validated['action_type'] ?? 'acknowledge',
            'action_label' => $validated['action_label'] ?? null,
            'condition_type' => $validated['condition_type'] ?? 'none',
            'condition_start' => $validated['condition_start'] ?? null,
            'condition_end' => $validated['condition_end'] ?? null,
        ];

        if ($request->boolean('remove_media') && $step->media_path) {
            Storage::disk('public')->delete($step->media_path);
            $updateData['media_path'] = null;
            $updateData['media_type'] = null;
        } elseif ($request->filled('media_path') && $request->input('media_path') !== $step->media_path) {
            $updateData['media_path'] = $validated['media_path'];
            $updateData['media_type'] = $validated['media_type'];
        }

        $step->update($updateData);

        return redirect()->route('guide.edit', $edit_token)
            ->with('success', 'Étape mise à jour.');
    }

    public function destroy(string $edit_token, Step $step): RedirectResponse
    {
        $guide = Guide::where('edit_token', $edit_token)->firstOrFail();
        abort_if($step->guide_id !== $guide->id, 404);

        if ($step->media_path) {
            Storage::disk('public')->delete($step->media_path);
        }

        $step->delete();

        $guide->steps()->orderBy('order')->get()->each(function (Step $s, int $index) {
            $s->update(['order' => $index + 1]);
        });

        return redirect()->route('guide.edit', $edit_token)
            ->with('success', 'Étape supprimée.');
    }

    public function reorder(Request $request, string $edit_token): JsonResponse
    {
        $guide = Guide::where('edit_token', $edit_token)->firstOrFail();

        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:steps,id',
        ]);

        foreach ($validated['order'] as $position => $stepId) {
            Step::where('id', $stepId)
                ->where('guide_id', $guide->id)
                ->update(['order' => $position + 1]);
        }

        return response()->json(['success' => true]);
    }

    public function uploadMedia(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:51200',
            'guide_id' => 'required|integer|exists:guides,id',
        ]);

        $file = $request->file('file');
        $guideId = $request->input('guide_id');
        $extension = strtolower($file->getClientOriginalExtension());

        $imageExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $videoExtensions = ['mp4', 'webm', 'mov'];

        if (in_array($extension, $imageExtensions)) {
            $mediaType = 'image';
        } elseif (in_array($extension, $videoExtensions)) {
            $mediaType = 'video';
        } else {
            return response()->json(['error' => 'Format non supporté.'], 422);
        }

        $path = $file->store("guides/{$guideId}", 'public');

        return response()->json([
            'success' => true,
            'path' => $path,
            'url' => asset('storage/'.$path),
            'media_type' => $mediaType,
        ]);
    }

    private function sanitizeHtml(?string $html): string
    {
        if (! $html) {
            return '';
        }

        return strip_tags($html, '<p><br><strong><em><b><i><u><h2><h3><ul><ol><li><a><blockquote>');
    }
}
