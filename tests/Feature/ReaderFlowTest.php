<?php

use App\Models\Guide;
use App\Models\GuideSession;
use App\Models\Step;
use Illuminate\Cookie\Middleware\EncryptCookies;

test('published guide is accessible via slug', function () {
    $guide = Guide::factory()->published()->create();
    Step::factory()->create(['guide_id' => $guide->id]);

    $this->get("/g/{$guide->slug}")
        ->assertStatus(200)
        ->assertSee($guide->title);
});

test('draft guide is not accessible via slug', function () {
    $guide = Guide::factory()->create();

    $this->get("/g/{$guide->slug}")
        ->assertStatus(404);
});

test('visiting a guide creates a session', function () {
    $guide = Guide::factory()->published()->create();
    Step::factory()->create(['guide_id' => $guide->id]);

    $this->get("/g/{$guide->slug}");

    expect(GuideSession::count())->toBe(1);
});

test('can complete a step', function () {
    $guide = Guide::factory()->published()->create();
    $step = Step::factory()->create(['guide_id' => $guide->id]);

    $session = GuideSession::create([
        'guide_id' => $guide->id,
        'reader_token' => 'test-token',
        'reader_ip' => '127.0.0.1',
        'started_at' => now(),
    ]);

    $this->disableCookieEncryption()
        ->withoutMiddleware(EncryptCookies::class)
        ->withCookie('guidly_reader', 'test-token')
        ->post("/g/{$guide->slug}/step/{$step->id}/complete", [], ['Accept' => 'application/json'])
        ->assertJson(['success' => true, 'completed_count' => 1]);
});

test('completing all steps marks guide as complete', function () {
    $guide = Guide::factory()->published()->create();
    $step = Step::factory()->create(['guide_id' => $guide->id]);

    $session = GuideSession::create([
        'guide_id' => $guide->id,
        'reader_token' => 'test-token',
        'reader_ip' => '127.0.0.1',
        'started_at' => now(),
    ]);

    $this->disableCookieEncryption()
        ->withoutMiddleware(EncryptCookies::class)
        ->withCookie('guidly_reader', 'test-token')
        ->post("/g/{$guide->slug}/step/{$step->id}/complete", [], ['Accept' => 'application/json'])
        ->assertJson(['is_guide_complete' => true]);

    expect($session->fresh()->completed_at)->not->toBeNull();
});

test('step from another guide cannot be completed', function () {
    $guide1 = Guide::factory()->published()->create();
    $guide2 = Guide::factory()->published()->create();
    $step = Step::factory()->create(['guide_id' => $guide2->id]);
    Step::factory()->create(['guide_id' => $guide1->id]);

    $session = GuideSession::create([
        'guide_id' => $guide1->id,
        'reader_token' => 'test-token',
        'reader_ip' => '127.0.0.1',
        'started_at' => now(),
    ]);

    $this->disableCookieEncryption()
        ->withoutMiddleware(EncryptCookies::class)
        ->withCookie('guidly_reader', 'test-token')
        ->post("/g/{$guide1->slug}/step/{$step->id}/complete", [], ['Accept' => 'application/json'])
        ->assertStatus(404);
});

test('preview mode works', function () {
    $guide = Guide::factory()->create();
    Step::factory()->create(['guide_id' => $guide->id]);

    $this->get("/guide/{$guide->edit_token}/preview")
        ->assertStatus(200)
        ->assertSeeText('aperçu', false);
});

test('share page loads for published guide', function () {
    $guide = Guide::factory()->published()->create();

    $this->get("/guide/{$guide->edit_token}/share")
        ->assertStatus(200)
        ->assertSee($guide->slug);
});

test('stats page loads', function () {
    $guide = Guide::factory()->published()->create();
    Step::factory()->create(['guide_id' => $guide->id]);

    $this->get("/guide/{$guide->edit_token}/stats")
        ->assertStatus(200)
        ->assertSee('Statistiques');
});
