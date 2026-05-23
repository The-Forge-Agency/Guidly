<?php

use App\Models\Guide;

test('can create up to 3 guides', function () {
    for ($i = 1; $i <= 3; $i++) {
        $this->post('/create', ['title' => "Guide {$i}"])
            ->assertRedirect();
    }

    expect(Guide::count())->toBe(3);
});

test('fourth guide is blocked', function () {
    Guide::factory()->count(3)->create(['creator_ip' => '127.0.0.1']);

    $this->post('/create', ['title' => 'Guide 4'])
        ->assertRedirect(route('guide.create'));

    expect(Guide::count())->toBe(3);
});

test('create page shows limit reached when at max', function () {
    Guide::factory()->count(3)->create(['creator_ip' => '127.0.0.1']);

    $this->get('/create')
        ->assertSee('Limite atteinte');
});

test('create page shows guide counter', function () {
    Guide::factory()->count(2)->create(['creator_ip' => '127.0.0.1']);

    $this->get('/create')
        ->assertSee('2/3');
});
