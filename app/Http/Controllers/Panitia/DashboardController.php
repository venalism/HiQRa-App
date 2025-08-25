<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;

class DashboardController extends Controller
{
    public function index()
    {
        $panitia = Auth::guard('panitia')->user();

        // 1. Ambil SEMUA data absensi panitia untuk statistik
        $absensi = Absensi::where('panitia_id', $panitia->id)->get();
        
        // Hitung status kehadiran
        $hadirCount = $absensi->whereNotNull('waktu_hadir')->count();
        $izinCount = $absensi->where('keterangan', 'Izin')->count();
        $sakitCount = $absensi->whereIn('keterangan', ['Sakit', 'tidak_hadir'])->count();
        $totalStatus = $hadirCount + $izinCount + $sakitCount;

        $hadirPercentage = ($totalStatus > 0) ? round(($hadirCount / $totalStatus) * 100) : 0;
        
        // 2. Ambil HANYA 5 data riwayat absensi terbaru dengan eager loading
        $riwayatAbsensi = Absensi::where('panitia_id', $panitia->id)
                                 ->with('kegiatan')
                                 ->orderBy('created_at', 'desc')
                                 ->take(5)
                                 ->get();

        return view('panitia.dashboard', [
            'panitia' => $panitia,
            'hadirCount' => $hadirCount,
            'izinCount' => $izinCount,
            'sakitCount' => $sakitCount,
            'hadirPercentage' => $hadirPercentage,
            'riwayatAbsensi' => $riwayatAbsensi,
        ]);
    }
}