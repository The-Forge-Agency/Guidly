<?php

use App\Models\Guide;
use App\Models\GuideSession;
use App\Models\Step;
use App\Models\StepCompletion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

function createBellecourGuide(): Guide
{
    $guide = Guide::factory()->published()->create(['slug' => 'bellecour']);
    Step::factory()->count(3)->sequence(
        ['order' => 0],
        ['order' => 1],
        ['order' => 2],
    )->create(['guide_id' => $guide->id]);

    return $guide;
}

function completeGuideSession(GuideSession $session, Guide $guide): void
{
    foreach ($guide->steps as $step) {
        StepCompletion::create([
            'guide_session_id' => $session->id,
            'step_id' => $step->id,
            'completed_at' => now(),
        ]);
    }
    $session->update(['completed_at' => now()]);
}

test('new user sees fresh bellecour guide', function (): void {
    createBellecourGuide();

    $response = $this->get('/g/bellecour');

    $response->assertOk();
    $response->assertViewHas('completedStepIds', []);
});

test('completed bellecour guide shows restart button', function (): void {
    $guide = createBellecourGuide();

    $this->get('/g/bellecour')->assertOk();

    $session = GuideSession::where('guide_id', $guide->id)->first();
    completeGuideSession($session, $guide);

    $response = $this->withCookie('guidly_demo_bellecour', $session->reader_token)
        ->get('/g/bellecour');

    $response->assertOk();
    $response->assertViewHas('completedStepIds', fn ($ids) => count($ids) === 3);
    $response->assertSee('Recommencer le guide');
});

test('restart=1 gives a completely fresh guide even with existing cookie', function (): void {
    $guide = createBellecourGuide();

    $this->get('/g/bellecour')->assertOk();

    $session = GuideSession::where('guide_id', $guide->id)->first();
    completeGuideSession($session, $guide);

    $response = $this->withCookie('guidly_demo_bellecour', $session->reader_token)
        ->get('/g/bellecour?restart=1');

    $response->assertOk();
    $response->assertViewHas('completedStepIds', []);
});

test('old guidly_reader cookie is ignored for bellecour', function (): void {
    $guide = createBellecourGuide();

    $oldToken = (string) Str::uuid();
    $oldSession = GuideSession::create([
        'guide_id' => $guide->id,
        'reader_token' => $oldToken,
        'reader_ip' => '127.0.0.1',
        'started_at' => now(),
    ]);
    completeGuideSession($oldSession, $guide);

    $response = $this->withCookie('guidly_reader', $oldToken)
        ->get('/g/bellecour');

    $response->assertOk();
    $response->assertViewHas('completedStepIds', []);
});

test('regular guide still uses persistent cookie', function (): void {
    $guide = Guide::factory()->published()->create(['slug' => 'testguide']);
    Step::factory()->create(['guide_id' => $guide->id, 'order' => 0]);

    $this->get('/g/testguide')->assertOk();

    $session = GuideSession::where('guide_id', $guide->id)->first();

    $response = $this->withCookie('guidly_reader', $session->reader_token)
        ->get('/g/testguide');

    $response->assertOk();
    $response->assertDontSee('Recommencer le guide');
});
