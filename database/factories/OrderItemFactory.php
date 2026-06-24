<?php
// database/factories/OrderItemFactory.php
 namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        $product  = Product::inRandomOrder()->first();
        $quantity = $this->faker->numberBetween(1, 5);
        $price    = $product->price;

        return [
            // order_id diisi dari luar saat create
            'product_id' => $product->id,
            'quantity'   => $quantity,
            'unit_price' => $price,
            'subtotal'   => $quantity * $price,
        ];
    }
}



