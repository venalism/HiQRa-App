<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta;
use App\Models\Panitia;
use App\Models\Kegiatan;
use App\Models\Absensi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    /**
     * Menampilkan halaman pemindaian QR code.
     */
    public function scan()
    {
        $kegiatan = Kegiatan::latest()->first();

        if (!$kegiatan) {
            return back()->with('error', 'Tidak ada data kegiatan yang bisa digunakan untuk absensi.');
        }
        
        return view('absensi.scan', compact('kegiatan'));
    }

    /**
     * Menyimpan data absensi baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
            'kegiatan_id' => 'required|integer|exists:kegiatan,id',
        ]);

        $barcode = $request->input('barcode');
        $kegiatanId = $request->input('kegiatan_id');
        $entity = null;
        $entityType = null;

        // Cek barcode di tabel Peserta
        $peserta = Peserta::where('barcode', $barcode)->first();
        if ($peserta) {
            $entity = $peserta;
            $entityType = 'peserta';
        } else {
            // Jika tidak, cek di tabel Panitia
            $panitia = Panitia::where('barcode', $barcode)->first();
            if ($panitia) {
                $entity = $panitia;
                $entityType = 'panitia';
            }
        }

        // Jika barcode tidak ditemukan sama sekali
        if (!$entity) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal! Barcode tidak valid atau tidak terdaftar.'
            ], 404);
        }

        // Cek absensi ganda
        $existingAbsensi = Absensi::where('kegiatan_id', $kegiatanId)
            ->where(function ($query) use ($entity, $entityType) {
                if ($entityType === 'peserta') {
                    $query->where('peserta_id', $entity->id);
                } else {
                    $query->where('panitia_id', $entity->id);
                }
            })
            ->first();

        if ($existingAbsensi) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal! ' . $entity->nama . ' sudah melakukan absensi sebelumnya.'
            ], 409);
        }

        // Menyiapkan data untuk disimpan
        $dataToCreate = [
            'kegiatan_id' => $kegiatanId,
            'user_id' => Auth::id(), // ID user (panitia) yang melakukan scan
            'waktu_hadir' => Carbon::now(),
            'metode' => 'barcode',
            'status' => 'hadir',
        ];

        // Mengisi kolom yang sesuai berdasarkan tipe entitas
        if ($entityType === 'peserta') {
            $dataToCreate['peserta_id'] = $entity->id;
            $dataToCreate['panitia_id'] = null; // Pastikan kolom lain null
        } else { // Jika panitia
            $dataToCreate['panitia_id'] = $entity->id;
            $dataToCreate['peserta_id'] = null; // Pastikan kolom lain null
        }
        
        // Simpan data absensi
        $absensi = Absensi::create($dataToCreate);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil! Kehadiran ' . $entity->nama . ' (' . ucfirst($entityType) . ') telah dicatat.',
            'data' => [
                'nama' => $entity->nama,
                'waktu_hadir' => $absensi->waktu_hadir->format('d-m-Y H:i:s')
            ]
        ]);
    }
}