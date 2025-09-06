<?php

namespace App\Exports;

use App\Models\Peserta;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PesertaAbsensiExport implements FromCollection, WithHeadings, WithMapping
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
        $query = Peserta::query()
            ->leftJoin('kelas', 'peserta.kelas_id', '=', 'kelas.id')
            ->leftJoin('prodis', 'kelas.prodi_id', '=', 'prodis.id');

        if ($this->request->filled('kegiatan_id')) {
            $kegiatanId = $this->request->kegiatan_id;

            $selectedKegiatan = Kegiatan::with('targetKelas')->find($kegiatanId);
            if ($selectedKegiatan && $selectedKegiatan->targetKelas()->exists()) {
                $query->whereIn('peserta.kelas_id', $selectedKegiatan->targetKelas->pluck('id'));
            }

            $query->leftJoin('absensi', function($join) use ($kegiatanId) {
                $join->on('peserta.id', '=', 'absensi.peserta_id')
                    ->where('absensi.kegiatan_id', '=', $kegiatanId);
            });
        }

        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where('peserta.nama', 'like', "%{$search}%");
        }

        return $query->select(
            'peserta.nama',
            'peserta.email',
            'peserta.npm',
            'peserta.no_hp',
            'kelas.nama as nama_kelas',
            'prodis.nama as nama_prodi',
            DB::raw("COALESCE(absensi.status, 'tidak_hadir') as status"),
            'absensi.waktu_hadir',
            'absensi.keterangan'
        )->orderBy('peserta.nama', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Peserta',
            'Email',
            'NPM',
            'No HP',
            'Kelas',
            'Prodi',
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
            $row->nama_kelas ?? 'N/A',
            $row->nama_prodi ?? 'N/A',
            $this->namaKegiatan ?? 'N/A', // selalu isi walau belum ada absensi
            ucfirst(str_replace('_', ' ', $row->status ?? 'tidak_hadir')),
            $row->waktu_hadir ? date('d-m-Y H:i:s', strtotime($row->waktu_hadir)) : '-',
            $row->keterangan ?? '-',
        ];
    }
}