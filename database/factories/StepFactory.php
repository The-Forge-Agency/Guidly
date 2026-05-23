<?php

namespace Database\Factories;

use App\Enums\ActionType;
use App\Enums\ConditionType;
use App\Models\Guide;
use App\Models\Step;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Step>
 */
class StepFactory extends Factory
{
    public function definition(): array
    {
        return [
            'guide_id' => Guide::factory(),
            'title' => fake()->sentence(2),
            'content_html' => '<p>'.fake()->paragraph().'</p>',
            'order' => 0,
            'action_type' => ActionType::Acknowledge,
            'condition_type' => ConditionType::None,
        ];
    }
}
