<?php

namespace Database\Factories;

use App\Models\Participant;
use App\Models\SavingTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SavingTransaction>
 */
class SavingTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'participant_id' => Participant::factory(),
            'type' => $this->faker->randomElement(['deposit', 'withdrawal']),
            'amount' => $this->faker->numberBetween(10000, 100000),
            'note' => $this->faker->optional()->sentence(),
            'transaction_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'created_by' => User::factory(),
        ];
    }
}
