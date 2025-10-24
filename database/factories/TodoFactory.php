<?php

namespace Database\Factories;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    protected $model = Todo::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $arStatus = [
            'pending',
            'open',
            'in_progress',
            'completed'
        ];
        $arPrior = [
            'low',
            'medium',
            'high',
        ];

        $tStat = rand(0,3);
        $tPrior = rand(0,2);

        return [
            'title' => $this->faker->sentence(3),
            'assignee' => $this->faker->optional()->name(),
            'due_date' => $this->faker->dateTimeBetween('+1 day', '+1 month'),
            'time_tracked' => rand(0,7200),
            'status' => $arStatus[$tStat],
            'priority' => $arPrior[$tPrior],
        ];
    }
}
