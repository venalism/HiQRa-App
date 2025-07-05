<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta;
use App\Models\Kegiatan;
use App\Models\Absensi;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    /**
     * Display the QR code scanning page.
     *
     * @return \Illuminate\View\View
     */
    public function scan()
    {
        // Untuk contoh ini, kita ambil kegiatan terakhir yang dibuat sebagai kegiatan aktif.
        // Dalam aplikasi nyata, Anda mungkin perlu mekanisme untuk memilih kegiatan.
        $kegiatan = Kegiatan::latest()->first();

        if (!$kegiatan) {
            return back()->with('error', 'Tidak ada data kegiatan yang bisa digunakan untuk absensi.');
        }
        
        return view('absensi.scan', compact('kegiatan'));
    }

    /**
     * Store a newly created attendance record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string|exists:peserta,barcode',
            'kegiatan_id' => 'required|integer|exists:kegiatan,id',
        ]);

        $barcode = $request->input('barcode');
        $kegiatanId = $request->input('kegiatan_id');

        $peserta = Peserta::where('barcode', $barcode)->firstOrFail();

        // Cek apakah peserta sudah pernah absen di kegiatan ini
        $existingAbsensi = Absensi::where('peserta_id', $peserta->id)
                                  ->where('kegiatan_id', $kegiatanId)
                                  ->first();

        if ($existingAbsensi) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal! Peserta ' . $peserta->nama . ' sudah melakukan absensi sebelumnya.'
            ], 409); // 409 Conflict
        }

        // Simpan data absensi
        $absensi = Absensi::create([
            'peserta_id' => $peserta->id,
            'kegiatan_id' => $kegiatanId,
            'user_id' => Auth::id(), // Mengambil ID panitia yang sedang login
            'waktu_hadir' => Carbon::now(),
            'metode' => 'barcode',
            'status' => 'hadir',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil! Kehadiran ' . $peserta->nama . ' telah dicatat.',
            'data' => [
                'nama' => $peserta->nama,
                'waktu_hadir' => $absensi->waktu_hadir->format('d-m-Y H:i:s')
            ]
        ]);
    }
}
