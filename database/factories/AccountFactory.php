<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\AccountType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company() . ' Account',
            'code' => $this->faker->unique()->numerify('#.#.#'),
            'account_type_id' => AccountType::factory(),
            'parent_id' => null,
            'is_active' => true,
            'is_operable' => true,
        ];
    }
}
