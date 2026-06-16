<?php


// database/seeders/ProductSeeder.php


namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [


            // Elektronik (category_id = 1)
            [
                'category_id' => 1,
                'name'        => 'Laptop ASUS VivoBook 15',
                'price'       => 8500000,
                'stock'       => 10,
            ],
            [
                'category_id' => 1,
                'name'        => 'Smartphone Samsung Galaxy A54',
                'price'       => 4500000,
                'stock'       => 25,
            ],
            [
                'category_id' => 1,
                'name'        => 'Earphone JBL Tune 230NC',
                'price'       => 650000,
                'stock'       => 50,
            ],
            [
                'category_id' => 1,
                'name'        => 'Monitor LG 24 inch Full HD',
                'price'       => 2800000,
                'stock'       => 8,
            ],


            // Fashion (category_id = 2)
            [
                'category_id' => 2,
                'name'        => 'Kaos Polos Premium Cotton',
                'price'       => 85000,
                'stock'       => 200,
            ],
            [
                'category_id' => 2,
                'name'        => 'Kemeja Flanel Tartan',
                'price'       => 195000,
                'stock'       => 75,
            ],
            [
                'category_id' => 2,
                'name'        => 'Sneakers Running Adidas',
                'price'       => 1200000,
                'stock'       => 30,
            ],


            // Makanan (category_id = 3)
            [
                'category_id' => 3,
                'name'        => 'Kopi Arabica Gayo 500g',
                'price'       => 125000,
                'stock'       => 100,
            ],
            [
                'category_id' => 3,
                'name'        => 'Madu Hutan Murni 350ml',
                'price'       => 95000,
                'stock'       => 60,
            ],
        ];


        foreach ($products as $product) {
            DB::table('products')->insert([
                'category_id' => $product['category_id'],
                'name'        => $product['name'],
                'slug'        => Str::slug($product['name']) . '-' . Str::lower(Str::random(4)),
                'price'       => $product['price'],
                'stock'       => $product['stock'],
                'status'      => 'active',
                'is_featured' => rand(0, 1) === 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }


        $this->command->info(
            '✅ ' . count($products) . ' produk berhasil dibuat.'
        );
    }
}
