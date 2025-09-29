<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Akun;
use App\Models\Properti;

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
        $akunId = Akun::query()->inRandomOrder()->value('id');
        $propertiId = Properti::query()->inRandomOrder()->value('id_properti');

        return [
            'id_akun' => $akunId ?? 1, // fallback minimal, idealnya sudah ada dari seeder
            'id_properti' => $propertiId ?? 1,
            'komentar' => fake()->paragraph(),
            'rating' => fake()->numberBetween(1, 5),
            'tanggal' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
        ];
    }
}
