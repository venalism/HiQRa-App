<?php

namespace App\Exports;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PanitiaAbsensiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        // Query HANYA untuk PANITIA
        $query = Absensi::with(['panitia.divisi', 'kegiatan', 'user'])
                        ->whereNotNull('panitia_id')
                        ->latest();

        if ($this->request->filled('kegiatan_id')) {
            $query->where('kegiatan_id', $this->request->kegiatan_id);
        }

        if ($this->request->filled('search')) {
            $searchTerm = $this->request->search;
            $query->whereHas('panitia', function ($q) use ($searchTerm) {
                $q->where('nama', 'like', "%{$searchTerm}%");
            });
        }
        
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Nama Panitia',
            'Email',
            'No_hp',
            'Divisi',
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
            $absensi->panitia->nama ?? 'N/A',
            $absensi->panitia->email ?? 'N/A',
            $absensi->panitia->no_hp ?? 'N/A',
            $absensi->panitia->divisi->nama ?? 'N/A',
            $absensi->kegiatan->nama_kegiatan ?? 'N/A',
            ucfirst(str_replace('_', ' ', $absensi->status)),
            $absensi->waktu_hadir,
            $absensi->user->name ?? 'N/A',
            $absensi->keterangan,
        ];
    }
}