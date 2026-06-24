<?php
// database/seeders/OrderSeeder.php
namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        if (Product::count() === 0) {
            $this->command->warn('Tidak ada produk! Jalankan ProductSeeder terlebih dahulu.');
            return;
        }

        $this->command->info('Membuat 1000 order beserta items...');
        $bar = $this->command->getOutput()->createProgressBar(1000);
        $bar->start();

        // Buat dalam chunk agar memori terkontrol
        collect(range(1, 10))->each(function () use ($bar) {
            // afterCreating() di OrderFactory otomatis membuat OrderItems
            Order::factory()
                ->count(100)
                ->create();

            $bar->advance(100);
        });

        $bar->finish();
        $this->command->newLine();
        $this->command->info('✅ 1000 order beserta order items berhasil dibuat.');
    }
}

