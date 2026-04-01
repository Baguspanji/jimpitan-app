<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => fake()->unique()->words(2, true),
            'unit' => fake()->randomElement(['Kg', 'Dos', 'Blek', 'Botol', 'Toples']),
            'weekly_price' => fake()->numberBetween(500, 30000),
            'type' => 'regular',
            'bonus_desc' => null,
        ];
    }

    public function package(): static
    {
        return $this->state(fn () => [
            'type' => 'package',
            'bonus_desc' => fake()->optional()->sentence(),
        ]);
    }
}
