<?php
// database/seeders/AdminUserSeeder.php


namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Gunakan firstOrCreate untuk menghindari duplikat
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'              => 'Administrator',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Admin user siap. Email: admin@example.com');
    }
}

