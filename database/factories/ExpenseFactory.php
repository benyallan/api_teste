<?php

namespace Database\Factories;

use App\Enums\ExpenseStatus;
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
        $requester = \App\Models\User::factory()->create();

        return [
            'description' => $this->faker->sentence,
            'value' => $this->faker->randomFloat(2, 0, 1000),
            'date' => $this->faker->date(),
            'requester_id' => $requester->id,
            'company_id' => $requester->company->id,
            'status' => ExpenseStatus::PENDING,
        ];
    }
}
