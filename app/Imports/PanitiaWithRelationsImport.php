<?php

namespace App\Imports;

use App\Models\Panitia;
use App\Models\Jabatan;
use App\Models\Divisi;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class PanitiaWithRelationsImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts
{
    public function model(array $row)
    {
        // Logika untuk Jabatan dan Divisi tetap sama
        $jabatan = Jabatan::firstOrCreate(['nama' => $row['jabatan']]);
        $divisi = Divisi::firstOrCreate(
            ['nama' => $row['divisi']],
            ['jabatan_id' => $jabatan->id]
        );

        // 1. Cari panitia yang sudah ada berdasarkan email dari Excel
        $panitia = Panitia::where('email', $row['email'])->first();

        // 2. Cek apakah panitia ditemukan
        if ($panitia) {
            // JIKA DITEMUKAN: Update data panitia yang ada
            $panitia->update([
                'nama'       => $row['nama'],
                'no_hp'      => $row['no_hp'],
                'jabatan_id' => $jabatan->id,
                'divisi_id'  => $divisi->id,
            ]);
            // Kembalikan model yang sudah diupdate
            return $panitia;
        } else {
            // JIKA TIDAK DITEMUKAN: Buat data panitia baru
            // Kita secara eksplisit hanya memilih data yang kita butuhkan,
            // dan mengabaikan 'id' atau kolom lain dari Excel.
            return new Panitia([
                'nama'       => $row['nama'],
                'email'      => $row['email'],
                'no_hp'      => $row['no_hp'],
                'jabatan_id' => $jabatan->id,
                'divisi_id'  => $divisi->id,
                'barcode'    => Str::random(10),
            ]);
        }
    }

    // Aturan validasi tidak perlu diubah, sudah benar
    public function rules(): array
    {
        return [
            'nama' => 'required|string',
            'email' => 'required|string|email|max:255',
            'no_hp' => 'required|numeric|digits_between:10,15',
            'jabatan' => 'required|string',
            'divisi' => 'required|string',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }
}