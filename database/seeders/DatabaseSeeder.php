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

        // Membuat Kegiatan
        Kegiatan::create([
            'nama_kegiatan' => 'Seminar Teknologi AI 2025',
            'deskripsi' => 'Seminar tahunan membahas perkembangan terbaru di dunia Artificial Intelligence.',
            'tanggal' => '2025-09-15',
            'waktu' => '09:00:00',
            'lokasi' => 'Gedung Serbaguna Kampus',
        ]);

        // Membuat Peserta
        Peserta::create([
            'nama' => 'Budi Santoso',
            'email' => 'budi.santoso@example.com',
            'no_hp' => '081234567890',
            'jabatan' => 'Mahasiswa',
            'barcode' => (string) Str::uuid(), // Generate unique barcode
        ]);
    }
}
