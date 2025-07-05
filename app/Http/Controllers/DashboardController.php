<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta;
use App\Models\Kegiatan;
use App\Models\Absensi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPeserta = Peserta::count();
        $totalKegiatan = Kegiatan::count();
        $hadirHariIni = Absensi::whereDate('waktu_hadir', Carbon::today())->count();
        $belumHadir = $totalPeserta - $hadirHariIni;

        return view('dashboard', compact('totalPeserta', 'totalKegiatan', 'hadirHariIni', 'belumHadir'));
    }
    //
}
