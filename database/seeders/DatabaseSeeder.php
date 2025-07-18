<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kegiatan;
use App\Models\Peserta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
                // Membuat Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Membuat Panitia
        User::create([
            'name' => 'Panitia',
            'email' => 'panitia@example.com',
            'password' => Hash::make('password'),
            'role' => 'panitia',
        ]);

    }
}
