<?php

namespace App\Http\Controllers;

use App\Models\Guide;
use App\Models\GuideSession;
use App\Models\Step;
use App\Models\StepCompletion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ReaderController extends Controller
{
    private const COOKIE_NAME = 'guidly_reader';

    private const COOKIE_LIFETIME = 525960;

    public function show(Request $request, string $slug): Response
    {
        $guide = Guide::published()->where('slug', $slug)->firstOrFail();
        $guide->load('steps');

        $isDemo = $guide->slug === 'bellecour';

        if ($isDemo) {
            $sessionKey = "guidly_reader_{$guide->id}";
            $readerToken = $request->session()->get($sessionKey);
            if (! $readerToken) {
                $readerToken = (string) Str::uuid();
                $request->session()->put($sessionKey, $readerToken);
            }
        } else {
            $readerToken = $request->cookie(self::COOKIE_NAME) ?? (string) Str::uuid();
        }

        $session = GuideSession::firstOrCreate(
            ['guide_id' => $guide->id, 'reader_token' => $readerToken],
            ['reader_ip' => $request->ip(), 'started_at' => now()]
        );

        $completedStepIds = $session->completions()->pluck('step_id')->toArray();

        $response = response()->view('guide.reader', [
            'guide' => $guide,
            'completedStepIds' => $completedStepIds,
            'isPreview' => false,
            'sessionId' => $session->id,
        ]);

        if (! $isDemo && ! $request->cookie(self::COOKIE_NAME)) {
            $response->withCookie(cookie(self::COOKIE_NAME, $readerToken, self::COOKIE_LIFETIME));
        }

        return $response;
    }

    public function completeStep(Request $request, string $slug, Step $step): JsonResponse
    {
        $guide = Guide::published()->where('slug', $slug)->firstOrFail();
        abort_if($step->guide_id !== $guide->id, 404);

        $readerToken = $this->getReaderToken($request, $guide);
        if (! $readerToken) {
            return response()->json(['error' => 'Session non trouvée.'], 400);
        }

        $session = GuideSession::where('guide_id', $guide->id)
            ->where('reader_token', $readerToken)
            ->firstOrFail();

        StepCompletion::firstOrCreate(
            ['guide_session_id' => $session->id, 'step_id' => $step->id],
            ['completed_at' => now()]
        );

        $totalSteps = $guide->steps()->count();
        $completedCount = $session->completions()->count();
        $isGuideComplete = $completedCount >= $totalSteps;

        if ($isGuideComplete && ! $session->completed_at) {
            $session->update(['completed_at' => now()]);
        }

        $nextStep = $guide->steps()
            ->whereNotIn('id', $session->completions()->pluck('step_id'))
            ->orderBy('order')
            ->first();

        return response()->json([
            'success' => true,
            'completed_count' => $completedCount,
            'total_steps' => $totalSteps,
            'is_guide_complete' => $isGuideComplete,
            'next_step_id' => $nextStep?->id,
        ]);
    }

    public function restart(Request $request, string $slug): Response
    {
        $guide = Guide::published()->where('slug', $slug)->firstOrFail();

        $sessionKey = "guidly_reader_{$guide->id}";
        $request->session()->forget($sessionKey);

        return redirect()->route('reader.show', $slug);
    }

    public function uploadAction(Request $request, string $slug, Step $step): JsonResponse
    {
        $guide = Guide::published()->where('slug', $slug)->firstOrFail();
        abort_if($step->guide_id !== $guide->id, 404);

        $readerToken = $this->getReaderToken($request, $guide);
        if (! $readerToken) {
            return response()->json(['error' => 'Session non trouvée.'], 400);
        }

        $session = GuideSession::where('guide_id', $guide->id)
            ->where('reader_token', $readerToken)
            ->firstOrFail();

        $actionData = [];
        if ($guide->slug === 'bellecour') {
            $actionData = ['demo' => true];
        } else {
            $request->validate([
                'photo' => 'required|image|max:5120',
            ]);
            $path = $request->file('photo')->store("reader-uploads/{$guide->id}", 'public');
            $actionData = ['photo_path' => $path];
        }

        StepCompletion::firstOrCreate(
            ['guide_session_id' => $session->id, 'step_id' => $step->id],
            ['completed_at' => now(), 'action_data' => $actionData]
        );

        $totalSteps = $guide->steps()->count();
        $completedCount = $session->completions()->count();
        $isGuideComplete = $completedCount >= $totalSteps;

        if ($isGuideComplete && ! $session->completed_at) {
            $session->update(['completed_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'completed_count' => $completedCount,
            'total_steps' => $totalSteps,
            'is_guide_complete' => $isGuideComplete,
        ]);
    }

    private function getReaderToken(Request $request, Guide $guide): ?string
    {
        if ($guide->slug === 'bellecour') {
            return $request->session()->get("guidly_reader_{$guide->id}");
        }

        return $request->cookie(self::COOKIE_NAME);
    }
}
