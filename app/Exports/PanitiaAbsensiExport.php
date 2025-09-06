<?php

namespace App\Exports;

use App\Models\Panitia;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PanitiaAbsensiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;
    protected $namaKegiatan;

    public function __construct(Request $request)
    {
        $this->request = $request;

        if ($this->request->filled('kegiatan_id')) {
            $kegiatan = Kegiatan::find($this->request->kegiatan_id);
            $this->namaKegiatan = $kegiatan ? $kegiatan->nama_kegiatan : null;
        }
    }

    public function collection()
    {
        $query = Panitia::query()
            ->leftJoin('divisis', 'panitia.divisi_id', '=', 'divisis.id')
            ->leftJoin('jabatans', 'divisis.jabatan_id', '=', 'jabatans.id');

        if ($this->request->filled('kegiatan_id')) {
            $kegiatanId = $this->request->kegiatan_id;

            $query->leftJoin('absensi', function ($join) use ($kegiatanId) {
                $join->on('panitia.id', '=', 'absensi.panitia_id')
                    ->where('absensi.kegiatan_id', '=', $kegiatanId);
            });
        }

        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where('panitia.nama', 'like', "%{$search}%");
        }

        return $query->select(
            'panitia.nama',
            'panitia.email',
            'panitia.npm',
            'panitia.no_hp',
            'divisis.nama as nama_divisi',
            'jabatans.nama as nama_jabatan',
            DB::raw("COALESCE(absensi.status, 'tidak_hadir') as status"),
            'absensi.waktu_hadir',
            'absensi.keterangan'
        )->orderBy('panitia.nama', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Panitia',
            'Email',
            'NPM',
            'No HP',
            'Divisi',
            'Jabatan',
            'Kegiatan',
            'Status',
            'Waktu Hadir',
            'Keterangan',
        ];
    }

    public function map($row): array
    {
        return [
            $row->nama ?? 'N/A',
            $row->email ?? 'N/A',
            $row->npm ?? 'N/A',
            $row->no_hp ?? 'N/A',
            $row->nama_divisi ?? 'N/A',
            $row->nama_jabatan ?? 'N/A',
            $this->namaKegiatan ?? 'N/A',
            ucfirst(str_replace('_', ' ', $row->status ?? 'tidak_hadir')),
            $row->waktu_hadir ? date('d-m-Y H:i:s', strtotime($row->waktu_hadir)) : '-',
            $row->keterangan ?? '-',
        ];
    }
}