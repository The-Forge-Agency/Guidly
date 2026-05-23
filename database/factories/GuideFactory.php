<?php

namespace Database\Factories;

use App\Enums\GuideStatus;
use App\Models\Guide;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Guide>
 */
class GuideFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'slug' => strtolower(Str::random(8)),
            'edit_token' => Str::random(32),
            'creator_email' => fake()->email(),
            'creator_cookie' => (string) Str::uuid(),
            'creator_ip' => fake()->ipv4(),
            'status' => GuideStatus::Draft,
            'require_completion' => true,
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'status' => GuideStatus::Published,
            'published_at' => now(),
        ]);
    }
}
