<?php

namespace App\Imports;

use App\Models\Panitia;
use App\Models\Jabatan;
use App\Models\Divisi;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class PanitiaWithRelationsImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts
{
    private $jabatans;
    private $divisis;

    public function __construct()
    {
        // Optimasi: Load semua jabatan dan divisi sekali saja di awal
        $this->jabatans = Jabatan::all()->keyBy('nama');
        $this->divisis = Divisi::all()->keyBy('nama');
    }

    public function model(array $row)
    {
        $jabatan = $this->jabatans->get($row['jabatan']) ?? Jabatan::firstOrCreate(['nama' => $row['jabatan']]);
        $divisi = $this->divisis->get($row['divisi']) ?? Divisi::firstOrCreate(
            ['nama' => $row['divisi']],
            ['jabatan_id' => $jabatan->id]
        );

        // 1. Cari panitia yang sudah ada berdasarkan NPM
        $panitia = Panitia::where('npm', $row['npm'])->first();

        // 2. Cek apakah panitia ditemukan
        if ($panitia) {
            // JIKA DITEMUKAN: Update data panitia yang ada
            $panitia->update([
                'nama'       => $row['nama'],
                'email'      => $row['email'],
                'no_hp'      => $row['no_hp'],
                'password'   => Hash::make($row['password']), // <-- PENTING: Password tetap di-hash saat update
                'jabatan_id' => $jabatan->id,
                'divisi_id'  => $divisi->id,
            ]);
            return null; // <-- Kembalikan null untuk mencegah error duplikasi
        } else {
            // JIKA TIDAK DITEMUKAN: Buat data panitia baru
            return new Panitia([
                'nama'       => $row['nama'],
                'email'      => $row['email'],
                'npm'        => $row['npm'],
                'no_hp'      => $row['no_hp'],
                'password'   => Hash::make($row['password']),
                'jabatan_id' => $jabatan->id,
                'divisi_id'  => $divisi->id,
                'barcode'    => Str::random(10),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'nama'     => 'required|string',
            'email'    => 'required|string|email|max:255|unique:panitia,email',
            'npm'      => 'required|numeric|digits_between:1,20|unique:panitia,npm',
            'no_hp'    => 'required|numeric|digits_between:10,20|unique:panitia,no_hp',
            'password' => 'required|min:8',
            'jabatan'  => 'required|string',
            'divisi'   => 'required|string',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }
}