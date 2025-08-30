<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use App\Models\Kegiatan;

class DashboardController extends Controller
{
    public function index()
    {
        $panitia = Auth::guard('panitia')->user();

        // Ambil 5 kegiatan terbaru + absensi panitia terkait (jika ada)
        $riwayatAbsensi = Kegiatan::with(['absensi' => function ($q) use ($panitia) {
                $q->where('panitia_id', $panitia->id);
            }])
            ->orderBy('tanggal', 'desc')
            ->take(5)
            ->get();

        // Hitung statistik absensi panitia
        $absensi = Absensi::where('panitia_id', $panitia->id)->get();

        $hadirCount = $absensi->whereNotNull('waktu_hadir')->count();
        $izinCount = $absensi->where('keterangan', 'Izin')->count();
        $sakitCount = $absensi->where('keterangan', 'Sakit')->count();
        $totalStatus = $hadirCount + $izinCount + $sakitCount;

        $hadirPercentage = ($totalStatus > 0) ? round(($hadirCount / $totalStatus) * 100) : 0;

        return view('panitia.dashboard', [
            'panitia' => $panitia,
            'hadirCount' => $hadirCount,
            'izinCount' => $izinCount,
            'sakitCount' => $sakitCount,
            'hadirPercentage' => $hadirPercentage,
            'riwayatAbsensi' => $riwayatAbsensi,
        ]);
    }

    public function dashboard()
    {
        $userId = Auth::id(); // panitia yang login

        // Ambil riwayat absensi panitia yang login
        $riwayatAbsensi = Absensi::with('kegiatan')
            ->where('panitia_id', $userId)
            ->orderByDesc('waktu_hadir')
            ->get();

        return view('panitia.dashboard', compact('riwayatAbsensi'));
    }

}