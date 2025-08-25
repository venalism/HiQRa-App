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
        $panitia = Auth::user();

        // Ambil data absensi untuk panitia yang sedang login
        $absensi = Absensi::where('panitia_id', $panitia->id)
                           ->with('kegiatan') // Eager load relasi kegiatan
                           ->get();

        // Hitung statistik
        $totalKegiatan = $absensi->pluck('kegiatan')->unique('id')->count();
        $totalHadir = $absensi->whereNotNull('jam_masuk')->count();

        return view('panitia.dashboard', [
            'panitia' => $panitia,
            'totalKegiatan' => $totalKegiatan,
            'totalHadir' => $totalHadir,
            'riwayatAbsensi' => $absensi
        ]);
    }
}
