<?php

namespace App\Imports;

use App\Models\Peserta;
use App\Models\Prodi;
use App\Models\Kelas;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class PesertaWithRelationsImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts
{
    public function model(array $row)
    {
        $prodi = Prodi::firstOrCreate(['nama' => $row['prodi']]);
        $kelas = Kelas::firstOrCreate(
            ['nama' => $row['kelas']],
            ['prodi_id' => $prodi->id]
        );

        // 1. Cari peserta yang sudah ada berdasarkan email dari Excel
        $peserta = Peserta::where('email', $row['email'])->first();

        // 2. Cek apakah peserta ditemukan
        if ($peserta) {
            // JIKA DITEMUKAN: Update data peserta yang ada
            $peserta->update([
                'nama'       => $row['nama'],
                'no_hp'      => $row['no_hp'],
                'prodi_id'   => $prodi->id,
                'kelas_id'   => $kelas->id,
            ]);
            // Kembalikan model yang sudah diupdate
            return $peserta;
        } else {
            // JIKA TIDAK DITEMUKAN: Buat data peserta baru
            // Kita secara eksplisit hanya memilih data yang kita butuhkan,
            // dan mengabaikan 'id' atau kolom lain dari Excel.
            return new Peserta([
                'nama'       => $row['nama'],
                'email'      => $row['email'],
                'no_hp'      => $row['no_hp'],
                'prodi_id'   => $prodi->id,
                'kelas_id'   => $kelas->id,
                'barcode'    => Str::random(10),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string',
            'email' => 'required|string|email|max:255',
            'no_hp' => 'required|numeric|digits_between:10,20|unique:peserta,no_hp',
            'prodi' => 'required|string',
            'kelas' => 'required|string',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }
}