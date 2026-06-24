<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    public function definition(): array
    {
        $type = $this->faker->randomElement(['percent', 'fixed']);

        return [
            'code' => strtoupper($this->faker->unique()->lexify('DISKON???')),
            'discount_type' => $type,
            'discount_value' => $type === 'percent' ? $this->faker->numberBetween(5, 50) : $this->faker->numberBetween(10000, 100000),
            'min_order' => $this->faker->randomElement([0, 50000, 100000, 150000]),
            'expires_at' => $this->faker->dateTimeBetween('now', '+2 months'),
            'usage_limit' => $this->faker->numberBetween(10, 100),
            'used_count' => $this->faker->numberBetween(0, 5),
        ];
    }
}
