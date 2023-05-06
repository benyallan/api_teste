<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => $this->faker->sentence,
            'value' => $this->faker->randomFloat(2, 0, 1000),
            'date' => $this->faker->date(),
            'user_id' => \App\Models\User::factory()->create()->id,
        ];
    }
}
