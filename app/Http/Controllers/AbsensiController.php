<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta;
use App\Models\Panitia; // Jangan lupa tambahkan ini
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
        // Ambil kegiatan terakhir sebagai sesi aktif.
        // Rekomendasi: Buat mekanisme untuk memilih kegiatan secara eksplisit.
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
            'barcode' => 'required|string', // Validasi 'exists' dihapus agar lebih fleksibel
            'kegiatan_id' => 'required|integer|exists:kegiatan,id',
        ]);

        $barcode = $request->input('barcode');
        $kegiatanId = $request->input('kegiatan_id');
        $entity = null; // Variabel untuk menampung model Peserta atau Panitia
        $entityType = null; // Untuk menandai tipe (peserta/panitia)

        // Cek apakah barcode milik seorang Peserta
        $peserta = Peserta::where('barcode', $barcode)->first();
        if ($peserta) {
            $entity = $peserta;
            $entityType = 'peserta';
        } else {
            // Jika tidak ditemukan di peserta, cek di panitia
            $panitia = Panitia::where('barcode', $barcode)->first();
            if ($panitia) {
                $entity = $panitia;
                $entityType = 'panitia';
            }
        }

        // Jika barcode tidak ditemukan di kedua tabel
        if (!$entity) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal! Barcode tidak valid atau tidak terdaftar.'
            ], 404); // 404 Not Found
        }

        // Cek apakah sudah pernah absen sebelumnya
        $absensiQuery = Absensi::where('kegiatan_id', $kegiatanId);

        if ($entityType === 'peserta') {
            $absensiQuery->where('peserta_id', $entity->id);
        } else {
            $absensiQuery->where('panitia_id', $entity->id);
        }

        $existingAbsensi = $absensiQuery->first();

        if ($existingAbsensi) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal! ' . $entity->nama . ' sudah melakukan absensi sebelumnya.'
            ], 409); // 409 Conflict
        }

        // Menyiapkan data untuk disimpan
        $dataToCreate = [
            'kegiatan_id' => $kegiatanId,
            'user_id' => Auth::id(), // ID user yang melakukan scan
            'waktu_hadir' => Carbon::now(),
            'metode' => 'barcode',
            'status' => 'hadir',
        ];

        if ($entityType === 'peserta') {
            $dataToCreate['peserta_id'] = $entity->id;
        } else {
            $dataToCreate['panitia_id'] = $entity->id;
        }

        // Simpan data absensi
        $absensi = Absensi::create($dataToCreate);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil! Kehadiran ' . $entity->nama . ' telah dicatat.',
            'data' => [
                'nama' => $entity->nama,
                'waktu_hadir' => $absensi->waktu_hadir->format('d-m-Y H:i:s')
            ]
        ]);
    }
}