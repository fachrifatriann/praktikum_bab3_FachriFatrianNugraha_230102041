<?php
// database/seeders/DatabaseSeeder.php- Seeder utama


namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Urutan penting: seeder yang punya FK harus dipanggil setelah parent-nya
        $this->call([
            AdminUserSeeder::class,   // 1. User dulu
            UserSeeder::class,   // 1. User dulu
            CategorySeeder::class,    // 2. Kategori
            ProductSeeder::class,     // 3. Produk (butuh kategori) ]
            OrderSeeder::class,     // 3. Produk (butuh kategori) ]
            ]
        );
    }
}

