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
        $peserta = Auth::user();

        // Ambil data absensi
        $absensi = Absensi::where('peserta_id', $peserta->id)
                           ->with('kegiatan')
                           ->get();

        // Hitung statistik
        $totalKegiatan = $absensi->pluck('kegiatan')->unique('id')->count();
        $totalHadir = $absensi->whereNotNull('jam_masuk')->count();

        return view('peserta.dashboard', [
            'peserta' => $peserta,
            'totalKegiatan' => $totalKegiatan,
            'totalHadir' => $totalHadir,
            'riwayatAbsensi' => $absensi
        ]);
    }
}
