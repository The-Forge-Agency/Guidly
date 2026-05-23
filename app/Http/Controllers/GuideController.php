<?php

namespace App\Http\Controllers;

use App\Enums\GuideStatus;
use App\Http\Requests\StoreGuideRequest;
use App\Http\Requests\UpdateGuideRequest;
use App\Models\Guide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GuideController extends Controller
{
    private const MAX_GUIDES = 3;

    private const COOKIE_NAME = 'guidly_owner';

    private const COOKIE_LIFETIME = 525960; // 1 year in minutes

    public function index(): View
    {
        return view('landing');
    }

    public function create(Request $request): View|RedirectResponse
    {
        $cookie = $request->cookie(self::COOKIE_NAME);
        $ip = $request->ip();

        $count = $this->getGuideCount($cookie, $ip);

        return view('guide.create', [
            'guideCount' => $count,
            'maxGuides' => self::MAX_GUIDES,
            'limitReached' => $count >= self::MAX_GUIDES,
        ]);
    }

    public function store(StoreGuideRequest $request): RedirectResponse
    {
        $cookie = $request->cookie(self::COOKIE_NAME) ?? (string) Str::uuid();
        $ip = $request->ip();

        if ($this->getGuideCount($cookie, $ip) >= self::MAX_GUIDES) {
            return redirect()->route('guide.create')
                ->with('error', 'Vous avez atteint la limite de '.self::MAX_GUIDES.' guides.');
        }

        $guide = Guide::create([
            'title' => $request->validated('title'),
            'description' => $request->validated('description'),
            'creator_email' => $request->validated('creator_email'),
            'slug' => Guide::generateUniqueSlug(),
            'edit_token' => Guide::generateEditToken(),
            'creator_cookie' => $cookie,
            'creator_ip' => $ip,
        ]);

        return redirect()->route('guide.edit', $guide->edit_token)
            ->withCookie(cookie(self::COOKIE_NAME, $cookie, self::COOKIE_LIFETIME));
    }

    public function edit(string $edit_token): View
    {
        $guide = Guide::where('edit_token', $edit_token)->firstOrFail();
        $guide->load('steps');

        return view('guide.edit', compact('guide'));
    }

    public function update(UpdateGuideRequest $request, string $edit_token): RedirectResponse
    {
        $guide = Guide::where('edit_token', $edit_token)->firstOrFail();

        $guide->update([
            'title' => $request->validated('title'),
            'description' => $request->validated('description'),
            'creator_email' => $request->validated('creator_email'),
        ]);

        if ($request->hasFile('cover_image')) {
            if ($guide->cover_image_path) {
                Storage::disk('public')->delete($guide->cover_image_path);
            }
            $path = $request->file('cover_image')->store("guides/{$guide->id}", 'public');
            $guide->update(['cover_image_path' => $path]);
        }

        return redirect()->route('guide.edit', $edit_token)
            ->with('success', 'Guide mis à jour.');
    }

    public function destroy(string $edit_token): RedirectResponse
    {
        $guide = Guide::where('edit_token', $edit_token)->firstOrFail();

        Storage::disk('public')->deleteDirectory("guides/{$guide->id}");
        $guide->delete();

        return redirect()->route('my-guides')
            ->with('success', 'Guide supprimé.');
    }

    public function publish(string $edit_token): RedirectResponse
    {
        $guide = Guide::where('edit_token', $edit_token)->firstOrFail();

        $guide->update([
            'status' => GuideStatus::Published,
            'published_at' => now(),
        ]);

        return redirect()->route('guide.edit', $edit_token)
            ->with('success', 'Guide publié !');
    }

    public function unpublish(string $edit_token): RedirectResponse
    {
        $guide = Guide::where('edit_token', $edit_token)->firstOrFail();

        $guide->update([
            'status' => GuideStatus::Draft,
            'published_at' => null,
        ]);

        return redirect()->route('guide.edit', $edit_token)
            ->with('success', 'Guide dépublié.');
    }

    public function share(string $edit_token): View
    {
        $guide = Guide::where('edit_token', $edit_token)->firstOrFail();

        return view('guide.share', compact('guide'));
    }

    public function stats(string $edit_token): View
    {
        $guide = Guide::where('edit_token', $edit_token)->firstOrFail();
        $guide->load('steps');

        $sessions = $guide->sessions()
            ->withCount('completions')
            ->orderByDesc('started_at')
            ->limit(20)
            ->get();

        $totalSessions = $guide->sessions()->count();
        $completedSessions = $guide->sessions()->whereNotNull('completed_at')->count();
        $completionRate = $totalSessions > 0 ? round(($completedSessions / $totalSessions) * 100) : 0;

        return view('guide.stats', compact('guide', 'sessions', 'totalSessions', 'completedSessions', 'completionRate'));
    }

    public function preview(string $edit_token): View
    {
        $guide = Guide::where('edit_token', $edit_token)->firstOrFail();
        $guide->load('steps');

        return view('guide.reader', [
            'guide' => $guide,
            'completedStepIds' => [],
            'isPreview' => true,
        ]);
    }

    public function myGuides(Request $request): View
    {
        $cookie = $request->cookie(self::COOKIE_NAME);

        $guides = $cookie
            ? Guide::where('creator_cookie', $cookie)
                ->withCount(['steps', 'sessions'])
                ->orderByDesc('created_at')
                ->get()
            : collect();

        return view('guide.my-guides', [
            'guides' => $guides,
            'maxGuides' => self::MAX_GUIDES,
        ]);
    }

    private function getGuideCount(?string $cookie, ?string $ip): int
    {
        return Guide::where(function ($query) use ($cookie, $ip) {
            if ($cookie) {
                $query->where('creator_cookie', $cookie);
            }
            if ($ip) {
                $query->orWhere('creator_ip', $ip);
            }
        })->count();
    }
}
