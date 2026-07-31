<?php

namespace Database\Factories;

use App\Models\account_types;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<account_types>
 */
class AccountTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word(),
            'normal_balance' => $this->faker->randomElement(['debit', 'credit']),
        ];
    }
}
