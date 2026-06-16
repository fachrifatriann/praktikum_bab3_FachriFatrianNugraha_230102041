<?php
// database/seeders/CategorySeeder.php
namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Elektronik', 'Fashion', 'Makanan & Minuman',
            'Olahraga', 'Otomotif', 'Rumah Tangga',
        ];


        foreach ($categories as $name) {
            DB::table('categories')->insert([
                'name'       => $name,
                'slug'       => Str::slug($name),
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        $this->command->info('✅ ' . count($categories) . ' kategori berhasil dibuat.');
    }
}

