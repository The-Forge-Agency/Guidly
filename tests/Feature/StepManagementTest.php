<?php

use App\Models\Guide;
use App\Models\Step;

test('can add a step to a guide', function () {
    $guide = Guide::factory()->create();

    $this->post("/guide/{$guide->edit_token}/steps", [
        'title' => 'Première étape',
    ])->assertRedirect();

    expect($guide->steps()->count())->toBe(1);
    expect($guide->steps->first()->title)->toBe('Première étape');
});

test('steps are auto-ordered', function () {
    $guide = Guide::factory()->create();

    $this->post("/guide/{$guide->edit_token}/steps", ['title' => 'Étape 1']);
    $this->post("/guide/{$guide->edit_token}/steps", ['title' => 'Étape 2']);

    $steps = $guide->steps()->orderBy('order')->get();
    expect($steps[0]->order)->toBe(1)
        ->and($steps[1]->order)->toBe(2);
});

test('step title is required', function () {
    $guide = Guide::factory()->create();

    $this->post("/guide/{$guide->edit_token}/steps", ['title' => ''])
        ->assertSessionHasErrors('title');
});

test('step can be updated', function () {
    $guide = Guide::factory()->create();
    $step = Step::factory()->create(['guide_id' => $guide->id]);

    $this->put("/guide/{$guide->edit_token}/steps/{$step->id}", [
        'title' => 'Titre modifié',
        'content_html' => '<p>Contenu mis à jour</p>',
        'action_type' => 'checkbox',
    ])->assertRedirect();

    $step->refresh();
    expect($step->title)->toBe('Titre modifié')
        ->and($step->content_html)->toBe('<p>Contenu mis à jour</p>');
});

test('step can be deleted', function () {
    $guide = Guide::factory()->create();
    $step = Step::factory()->create(['guide_id' => $guide->id]);

    $this->delete("/guide/{$guide->edit_token}/steps/{$step->id}")
        ->assertRedirect();

    expect(Step::count())->toBe(0);
});

test('remaining steps are reordered after deletion', function () {
    $guide = Guide::factory()->create();
    $step1 = Step::factory()->create(['guide_id' => $guide->id, 'order' => 1]);
    $step2 = Step::factory()->create(['guide_id' => $guide->id, 'order' => 2]);
    $step3 = Step::factory()->create(['guide_id' => $guide->id, 'order' => 3]);

    $this->delete("/guide/{$guide->edit_token}/steps/{$step2->id}");

    expect($step1->fresh()->order)->toBe(1)
        ->and($step3->fresh()->order)->toBe(2);
});

test('steps can be reordered via API', function () {
    $guide = Guide::factory()->create();
    $step1 = Step::factory()->create(['guide_id' => $guide->id, 'order' => 1]);
    $step2 = Step::factory()->create(['guide_id' => $guide->id, 'order' => 2]);

    $this->postJson("/guide/{$guide->edit_token}/steps/reorder", [
        'order' => [$step2->id, $step1->id],
    ])->assertJson(['success' => true]);

    expect($step1->fresh()->order)->toBe(2)
        ->and($step2->fresh()->order)->toBe(1);
});

test('cannot modify step from another guide', function () {
    $guide1 = Guide::factory()->create();
    $guide2 = Guide::factory()->create();
    $step = Step::factory()->create(['guide_id' => $guide2->id]);

    $this->put("/guide/{$guide1->edit_token}/steps/{$step->id}", [
        'title' => 'Hacked',
    ])->assertStatus(404);
});

test('html content is sanitized', function () {
    $guide = Guide::factory()->create();

    $this->post("/guide/{$guide->edit_token}/steps", [
        'title' => 'Test XSS',
        'content_html' => '<p>OK</p><script>alert("xss")</script>',
    ]);

    $step = $guide->steps->first();
    expect($step->content_html)->not->toContain('<script>');
});
