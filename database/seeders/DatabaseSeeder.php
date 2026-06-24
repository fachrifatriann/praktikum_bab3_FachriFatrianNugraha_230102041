<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// 📍 LOGIKA SOLUSI: Kita panggil modelnya di paling atas agar kodingan di bawah ringkas
use App\Models\Coupon;
use App\Models\Review;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Jalankan seeder data induk terlebih dahulu agar ID-nya bisa dipakai tabel review
        $this->call([
            AdminUserSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            OrderSeeder::class,
        ]);

        // 2. Menjalankan Challenge 1 & Challenge 2
        // Sekarang kodenya jauh lebih pendek dan bebas dari error path \
        Coupon::factory()->count(10)->create();

        // Sesuai perintah tantangan kedua: Buat 300 data review realistis
        Review::factory()->count(300)->create();
    }
}
