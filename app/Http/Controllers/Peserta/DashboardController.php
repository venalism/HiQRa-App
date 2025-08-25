<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;

class DashboardController extends Controller
{
    public function index()
    {
        $peserta = Auth::guard('peserta')->user();

        // 1. Ambil SEMUA data absensi peserta untuk statistik
        $absensi = Absensi::where('peserta_id', $peserta->id)->get();
        
        // Hitung status kehadiran
        $hadirCount = $absensi->whereNotNull('jam_masuk')->count();
        $izinCount = $absensi->where('keterangan', 'Izin')->count();
        $sakitCount = $absensi->where('keterangan', 'Sakit')->count();
        $totalStatus = $hadirCount + $izinCount + $sakitCount;

        $hadirPercentage = ($totalStatus > 0) ? round(($hadirCount / $totalStatus) * 100) : 0;
        
        // 2. Ambil HANYA 5 data riwayat absensi terbaru dengan eager loading
        $riwayatAbsensi = Absensi::where('peserta_id', $peserta->id)
                                 ->with('kegiatan') // <-- 'with' dipanggil di sini
                                 ->orderBy('created_at', 'desc')
                                 ->take(5)
                                 ->get(); // <-- 'get' dipanggil terakhir

        return view('peserta.dashboard', [
            'peserta' => $peserta,
            'hadirCount' => $hadirCount,
            'izinCount' => $izinCount,
            'sakitCount' => $sakitCount,
            'hadirPercentage' => $hadirPercentage,
            'riwayatAbsensi' => $riwayatAbsensi,
        ]);
    }
}