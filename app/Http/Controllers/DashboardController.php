<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta;
use App\Models\Kegiatan;
use App\Models\Absensi;
use App\Models\Panitia;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung total entitas
        $totalPeserta = Peserta::count();
        $totalPanitia = Panitia::count();
        $totalKegiatan = Kegiatan::count();

        // Menghitung total semua anggota (peserta + panitia)
        $totalAnggota = $totalPeserta + $totalPanitia;

        // Menghitung jumlah absensi yang masuk hari ini
        $hadirHariIni = Absensi::whereDate('waktu_hadir', Carbon::today())->count();

        // Menghitung yang belum hadir berdasarkan total anggota
        // Logika ini mengasumsikan absensi hari ini adalah untuk semua anggota
        $belumHadir = $totalAnggota - $hadirHariIni;

        // Memastikan nilai tidak negatif jika ada anomali data
        if ($belumHadir < 0) {
            $belumHadir = 0;
        }

        return view('dashboard', compact(
            'totalPeserta', 
            'totalPanitia',
            'totalKegiatan', 
            'hadirHariIni', 
            'belumHadir'
        ));
    }
}