<?php
// database/seeders/UserSeeder.php
 namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat 20 user biasa dengan factory
        User::factory()->count(20)->create();

        // Buat 10 user biasa unverified
        User::factory()->count(10)->unverified()->create();

        $this->command->info('✅ 30 user berhasil dibuat.');

    }
}

