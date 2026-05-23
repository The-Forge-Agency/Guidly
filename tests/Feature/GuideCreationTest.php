<?php

use App\Models\Guide;

test('landing page loads', function () {
    $this->get('/')->assertStatus(200)->assertSee('Guidly');
});

test('create page loads', function () {
    $this->get('/create')->assertStatus(200)->assertSee('Créez votre guide');
});

test('can create a guide with title only', function () {
    $response = $this->post('/create', ['title' => 'Mon guide test']);

    $response->assertRedirect();

    $guide = Guide::first();
    expect($guide)->not->toBeNull()
        ->and($guide->title)->toBe('Mon guide test')
        ->and($guide->slug)->toHaveLength(8)
        ->and($guide->edit_token)->toHaveLength(32);
});

test('can create a guide with all fields', function () {
    $response = $this->post('/create', [
        'title' => 'Guide complet',
        'description' => 'Une description du guide',
        'creator_email' => 'test@example.com',
    ]);

    $response->assertRedirect();

    $guide = Guide::first();
    expect($guide->title)->toBe('Guide complet')
        ->and($guide->description)->toBe('Une description du guide')
        ->and($guide->creator_email)->toBe('test@example.com');
});

test('title is required', function () {
    $this->post('/create', ['title' => ''])
        ->assertSessionHasErrors('title');
});

test('email must be valid if provided', function () {
    $this->post('/create', [
        'title' => 'Test',
        'creator_email' => 'invalid-email',
    ])->assertSessionHasErrors('creator_email');
});

test('guide is created as draft', function () {
    $this->post('/create', ['title' => 'Draft guide']);

    $guide = Guide::first();
    expect($guide->isPublished())->toBeFalse();
});

test('guide edit page loads with edit token', function () {
    $guide = Guide::factory()->create();

    $this->get("/guide/{$guide->edit_token}")
        ->assertStatus(200)
        ->assertSee($guide->title);
});

test('guide can be published and unpublished', function () {
    $guide = Guide::factory()->create();

    $this->post("/guide/{$guide->edit_token}/publish")
        ->assertRedirect();
    expect($guide->fresh()->isPublished())->toBeTrue();

    $this->post("/guide/{$guide->edit_token}/unpublish")
        ->assertRedirect();
    expect($guide->fresh()->isPublished())->toBeFalse();
});

test('guide can be deleted', function () {
    $guide = Guide::factory()->create();

    $this->delete("/guide/{$guide->edit_token}")
        ->assertRedirect(route('my-guides'));

    expect(Guide::count())->toBe(0);
});

test('guide metadata can be updated', function () {
    $guide = Guide::factory()->create();

    $this->put("/guide/{$guide->edit_token}", [
        'title' => 'Nouveau titre',
        'description' => 'Nouvelle description',
    ])->assertRedirect();

    $guide->refresh();
    expect($guide->title)->toBe('Nouveau titre')
        ->and($guide->description)->toBe('Nouvelle description');
});

test('my guides page loads', function () {
    $this->get('/mes-guides')->assertStatus(200);
});
