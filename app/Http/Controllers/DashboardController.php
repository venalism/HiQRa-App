<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peserta;
use App\Models\Kegiatan;
use App\Models\Absensi;
use App\Models\Panitia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        if ($user->role === 'admin') {
            // Admin sees global data
            $totalPeserta = Peserta::count();
            $totalPanitia = Panitia::count();
            $totalKegiatan = Kegiatan::count();
            $totalAnggota = $totalPeserta + $totalPanitia;
            $hadirHariIni = Absensi::whereDate('waktu_hadir', Carbon::today())->count();
            $belumHadir = $totalAnggota - $hadirHariIni;

            $data = [
                'totalPeserta' => $totalPeserta,
                'totalPanitia' => $totalPanitia,
                'totalKegiatan' => $totalKegiatan,
                'hadirHariIni' => $hadirHariIni,
                'belumHadir' => $belumHadir > 0 ? $belumHadir : 0,
            ];
        } else {
            // Logic for Panitia and Peserta
            $profile = null;
            if ($user->role === 'panitia') {
                $profile = Panitia::where('user_id', $user->id)->first();
            } else if ($user->role === 'peserta') {
                $profile = Peserta::where('user_id', $user->id)->first();
            }

            $totalKegiatanWajib = 0;
            $totalKehadiran = 0;

            if ($profile) {
                // Build the query for mandatory activities
                $kegiatanWajibQuery = Kegiatan::query()->where(function ($query) use ($profile) {
                    $query->whereNull('target_prodi')
                          ->whereNull('target_kelas')
                          ->whereNull('target_tingkat')
                          ->whereNull('target_jabatan');
                })->orWhere(function ($query) use ($profile) {
                    if ($profile->prodi) $query->orWhere('target_prodi', $profile->prodi);
                    if ($profile->kelas) $query->orWhere('target_kelas', $profile->kelas);
                    if ($profile->tingkat) $query->orWhere('target_tingkat', $profile->tingkat);
                    if ($profile->jabatan) $query->orWhere('target_jabatan', $profile->jabatan);
                });

                $kegiatanWajibIds = $kegiatanWajibQuery->pluck('id');
                $totalKegiatanWajib = $kegiatanWajibIds->count();

                // Count attendance for mandatory activities
                $totalKehadiran = Absensi::where('user_id', $user->id)
                                         ->whereIn('kegiatan_id', $kegiatanWajibIds)
                                         ->count();
            }

            $data = [
                'totalKegiatanWajib' => $totalKegiatanWajib,
                'totalKehadiran' => $totalKehadiran,
            ];
        }

        return view('dashboard', $data);
    }
}