<?php
// database/factories/OrderFactory.php

namespace Database\Factories;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    private static int $counter = 1;

    public function definition(): array
    {
        $status       = $this->faker->randomElement(['pending','processing','shipped','delivered','cancelled']);
        $shippingCost = $this->faker->randomElement([0, 9000, 15000, 25000, 35000]);
        $discount     = $this->faker->randomElement([0, 0, 0, 5000, 10000, 25000]);

        $paidAt    = in_array($status, ['processing','shipped','delivered'])
                        ? $this->faker->dateTimeBetween('-6 months', '-1 week')
                        : null;
        $shippedAt = in_array($status, ['shipped','delivered'])
                        ? $this->faker->dateTimeBetween($paidAt ?? '-5 months', 'now')
                        : null;

        return [
            'user_id'      => User::inRandomOrder()->value('id'),
            'order_number' => 'ORD-' . date('Ymd') . '-' . str_pad(self::$counter++, 4, '0', STR_PAD_LEFT),
            'status'       => $status,
            'subtotal'     => 0,     // dihitung ulang di afterCreating
            'shipping_cost'=> $shippingCost,
            'discount'     => $discount,
            'total'        => 0,     // dihitung ulang di afterCreating
            'notes'        => $this->faker->boolean(20) ? $this->faker->sentence() : null,
            'paid_at'      => $paidAt,
            'shipped_at'   => $shippedAt,
        ];
    }

    // Hook: dijalankan otomatis setelah order berhasil disimpan
    public function configure(): static
    {
        return $this->afterCreating(function (Order $order) {
            // Buat 1–5 order items menggunakan OrderItemFactory
            $items = OrderItem::factory()
                ->count(rand(1, 5))
                ->for($order)           // otomatis set order_id
                ->create();

            // Hitung subtotal dari actual items yang terbuat
            $subtotal = $items->sum('subtotal');

            $order->update([
                'subtotal' => $subtotal,
                'total'    => $subtotal + $order->shipping_cost - $order->discount,
            ]);
        });
    }

    public function delivered(): static
    {
        return $this->state(fn(array $a) => [
            'status'     => 'delivered',
            'paid_at'    => $this->faker->dateTimeBetween('-6 months', '-2 weeks'),
            'shipped_at' => $this->faker->dateTimeBetween('-2 weeks', '-3 days'),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn(array $a) => [
            'status'    => 'cancelled',
            'paid_at'   => null,
            'shipped_at'=> null,
        ]);
    }
}

