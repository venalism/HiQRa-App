<?php

namespace App\Imports;

use App\Models\Peserta;
use App\Models\Prodi;
use App\Models\Kelas;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Validation\Rule;

class PesertaWithRelationsImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts
{
    private $prodis;
    private $kelases;

    public function __construct()
    {
        // Optimasi: Load semua prodi dan kelas sekali saja di awal
        $this->prodis = Prodi::all()->keyBy('nama');
        $this->kelases = Kelas::all()->keyBy('nama');
    }

    public function model(array $row)
    {
        // Logika untuk Prodi dan Kelas tetap sama (sudah efisien)
        $prodi = $this->prodis->get($row['prodi']) ?? Prodi::firstOrCreate(['nama' => $row['prodi']]);
        $kelas = $this->kelases->get($row['kelas']) ?? Kelas::firstOrCreate(
            ['nama' => $row['kelas']],
            ['prodi_id' => $prodi->id]
        );

        // 1. Cari peserta yang sudah ada berdasarkan NPM dari Excel
        $peserta = Peserta::where('npm', $row['npm'])->first();

        // 2. Cek apakah peserta ditemukan
        if ($peserta) {
            // JIKA DITEMUKAN: Update data peserta yang ada
            $peserta->update([
                'nama'       => $row['nama'],
                'email'      => $row['email'],
                'no_hp'      => $row['no_hp'],
                'password'   => Hash::make($row['password']),
                'prodi_id'   => $prodi->id,
                'kelas_id'   => $kelas->id,
            ]);
            return null;
        } else {
            // JIKA TIDAK DITEMUKAN: Buat data peserta baru
            return new Peserta([
                'nama'       => $row['nama'],
                'email'      => $row['email'],
                'npm'        => $row['npm'],
                'no_hp'      => $row['no_hp'],
                'password'   => Hash::make($row['password']),
                'prodi_id'   => $prodi->id,
                'kelas_id'   => $kelas->id,
                'barcode'    => Str::random(10),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'nama'     => 'required|string',
            'email'    => 'required|string|email|max:255',
            'npm'      => 'required|numeric|digits_between:1,20|unique:peserta,npm',
            'no_hp'    => 'required|numeric|digits_between:10,20|unique:peserta,no_hp',
            'password' => 'required|min:8',
            'prodi'    => 'required|string',
            'kelas'    => 'required|string',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }
}