<?php
// database/factories/ProductFactory.php

namespace Database\Factories;

use App\Enums\ProductStatus;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->words(rand(2, 4), true);

        return [
            'category_id' => Category::query()->inRandomOrder()->value('id'),
            'name'         => ucwords($name),
            'slug'         => Str::slug($name),
            'price'        => $this->faker->randomFloat(2, 10000, 5000000),
            'stock'        => $this->faker->numberBetween(0, 500),
            'description'  => $this->faker->paragraphs(3, true),
            'status'       => $this->faker->randomElement(ProductStatus::cases()),
            'is_featured'  => $this->faker->boolean(20), // 20% kemungkinan true
            'weight'       => $this->faker->randomFloat(2, 0.1, 20),
            'rating'       => $this->faker->randomFloat(1, 1, 5),
            'view_count'   => $this->faker->numberBetween(0, 10000),
        ];
    }

    // State: produk aktif saja
    public function active(): static
    {
        return $this->state(['status' => ProductStatus::Active, 'stock' => rand(10, 100)]);
    }

    // State: produk featured
    public function featured(): static
    {
        return $this->state(['is_featured' => true]);
    }

    // State: stok habis
    public function outOfStock(): static
    {
        return $this->state(['status' => ProductStatus::OutOfStock, 'stock' => 0]);
    }


// State khusus untuk mengisi rating & review_count
    public function withRating(): static
    {
        return $this->state(fn(array $attributes) => [
            'rating'       => $this->faker->randomFloat(1, 1, 5),  // contoh: 3.7
            'review_count' => $this->faker->numberBetween(0, 500),
        ]);
    }


}
