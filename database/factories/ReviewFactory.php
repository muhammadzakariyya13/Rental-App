<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_akun' => fake()->numberBetween(1, 5),
            'id_properti' => fake()->numberBetween(1, 20),
            'komentar' => fake()->paragraph(),
            'rating' => fake()->numberBetween(1, 5),
            'tanggal' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'is_approved' => 1, // Auto-approve all reviews
        ];
    }
}
