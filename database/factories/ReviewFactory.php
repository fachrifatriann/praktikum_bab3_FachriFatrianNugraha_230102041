<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    public function definition(): array
    {
        $chance = $this->faker->numberBetween(1, 100);

        if ($chance <= 40) {
            // 40% kemungkinan dapat Bintang 5
            $rating = 5;
        } elseif ($chance <= 70) {
            // 30% kemungkinan dapat Bintang 4 (40 + 30 = 70)
            $rating = 4;
        } elseif ($chance <= 90) {
            // 20% kemungkinan dapat Bintang 3 (70 + 20 = 90)
            $rating = 3;
        } else {
            // 10% sisanya dapat Bintang 1 atau 2
            $rating = $this->faker->numberBetween(1, 2);
        }
        // ─────────────────────────────────────────────────────────────────────

        return [
            'user_id' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
            'product_id' => Product::query()->inRandomOrder()->value('id') ?? Product::factory(),
            'rating' => $rating, // Memasukkan variabel hasil persentase di atas
            'comment' => $this->faker->sentence(rand(5, 12)),
            'created_at' => $this->faker->dateTimeBetween('-1 months', 'now'),
        ];
    }
}
