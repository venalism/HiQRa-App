<?php

namespace App\Exports;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PesertaAbsensiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        // Query HANYA untuk PESERTA
        $query = Absensi::with(['peserta', 'kegiatan', 'user'])
                        ->whereNotNull('peserta_id')
                        ->latest();

        if ($this->request->filled('kegiatan_id')) {
            $query->where('kegiatan_id', $this->request->kegiatan_id);
        }

        if ($this->request->filled('search')) {
            $searchTerm = $this->request->search;
            $query->whereHas('peserta', function ($q) use ($searchTerm) {
                $q->where('nama', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }
        
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Nama Peserta',
            'Email',
            'No_hp',
            'Kegiatan',
            'Status',
            'Waktu Hadir',
            'Dicatat Oleh',
            'Keterangan',
        ];
    }

    public function map($absensi): array
    {
        return [
            $absensi->peserta->nama ?? 'N/A',
            $absensi->peserta->email ?? 'N/A',
            $absensi->peserta->no_hp ?? 'N/A',
            $absensi->kegiatan->nama_kegiatan ?? 'N/A',
            ucfirst(str_replace('_', ' ', $absensi->status)),
            $absensi->waktu_hadir,
            $absensi->user->name ?? 'N/A',
            $absensi->keterangan,
        ];
    }
}