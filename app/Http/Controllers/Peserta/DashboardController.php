<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use App\Models\Kegiatan;

class DashboardController extends Controller
{
    public function index()
    {
        $peserta = Auth::guard('peserta')->user();

        // Ambil 5 kegiatan terbaru + absensi peserta terkait (jika ada)
        $riwayatAbsensi = Kegiatan::with(['absensi' => function ($q) use ($peserta) {
                $q->where('peserta_id', $peserta->id);
            }])
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        // Hitung statistik absensi peserta
        $absensi = Absensi::where('peserta_id', $peserta->id)->get();

        $hadirCount = $absensi->whereNotNull('waktu_hadir')->count();
        $izinCount = $absensi->where('keterangan', 'Izin')->count();
        $sakitCount = $absensi->where('keterangan', 'Sakit')->count();
        $totalStatus = $hadirCount + $izinCount + $sakitCount;

        $hadirPercentage = ($totalStatus > 0) ? round(($hadirCount / $totalStatus) * 100) : 0;

        return view('peserta.dashboard', [
            'peserta' => $peserta,
            'hadirCount' => $hadirCount,
            'izinCount' => $izinCount,
            'sakitCount' => $sakitCount,
            'hadirPercentage' => $hadirPercentage,
            'riwayatAbsensi' => $riwayatAbsensi,
        ]);
    }

    public function dashboard()
    {
        $userId = Auth::id(); // peserta yang login

        // Ambil riwayat absensi peserta yang login
        $riwayatAbsensi = Absensi::with('kegiatan')
            ->where('peserta_id', $userId)
            ->orderByDesc('waktu_hadir')
            ->get();

        return view('peserta.dashboard', compact('riwayatAbsensi'));
    }

}