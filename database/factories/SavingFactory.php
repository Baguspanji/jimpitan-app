<?php

namespace Database\Factories;

use App\Models\Participant;
use App\Models\Saving;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Saving>
 */
class SavingFactory extends Factory
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
            'balance' => $this->faker->numberBetween(0, 500000),
        ];
    }
}
