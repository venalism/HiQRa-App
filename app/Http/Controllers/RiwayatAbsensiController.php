<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Kegiatan;
use App\Models\Peserta;
use App\Models\Panitia;
use Illuminate\Support\Facades\Auth;
use App\Exports\PesertaAbsensiExport;
use App\Exports\PanitiaAbsensiExport;
use Maatwebsite\Excel\Facades\Excel;

class RiwayatAbsensiController extends Controller
{
    public function riwayatPeserta(Request $request)
    {
        // 🎯 Langkah 1: Ambil data kegiatan untuk dropdown filter di view
        $kegiatan = Kegiatan::orderBy('nama_kegiatan', 'asc')->get();

        // 🎯 Langkah 2: Ambil ID kegiatan dari request
        $kegiatanId = $request->input('kegiatan_id');
        $searchTerm = $request->input('search');

        // 🎯 Langkah 3: Mulai query dari tabel Peserta, bukan Absensi
        $query = Peserta::query();

        // 🎯 Langkah 4: Terapkan filter berdasarkan target kegiatan jika ada
        if ($kegiatanId) {
            $selectedKegiatan = Kegiatan::with(['prodis', 'kelas'])->find($kegiatanId);

            // Cek apakah kegiatan ini punya target (prodi atau kelas)
            // KODE YANG DIPERBAIKI DI SINI
            if ($selectedKegiatan) {
                // Periksa apakah ada relasi prodi atau kelas yang terhubung
                $hasProdis = $selectedKegiatan->prodis->isNotEmpty();
                $hasKelas = $selectedKegiatan->kelas != null; // Cek apakah objek kelas ada
                
                if ($hasProdis || $hasKelas) {
                     $query->where(function($q) use ($selectedKegiatan) {
                        // Filter berdasarkan prodi yang ditargetkan
                        $prodiIds = $selectedKegiatan->prodis->pluck('id');
                        if ($prodiIds->isNotEmpty()) {
                            $q->whereIn('prodi_id', $prodiIds);
                        }
                        
                        // Filter berdasarkan kelas yang ditargetkan
                        // Periksa apakah objek kelas ada sebelum mengambil ID
                        if ($selectedKegiatan->kelas) {
                            $q->orWhere('kelas_id', $selectedKegiatan->kelas->id);
                        }
                    });
                }
            }
        }

        // 🎯 Langkah 5: Gabungkan (join) dengan tabel absensi untuk cek status
        $query->leftJoin('absensi', function($join) use ($kegiatanId) {
            $join->on('peserta.id', '=', 'absensi.peserta_id')
                ->where('absensi.kegiatan_id', '=', $kegiatanId)
                ->where('absensi.status', '!=', 'belum_hadir');
        });
        
        // 🎯 Langkah 6: Pilih kolom yang dibutuhkan dan tambahkan status
        $pesertaList = $query->select(
            'peserta.id', 
            'peserta.nama', 
            'peserta.npm', 
            'absensi.status',
            'absensi.keterangan'
        )
        ->orderBy('peserta.nama', 'asc');

        // 🎯 Langkah 7: Terapkan filter pencarian nama jika ada
        if ($searchTerm) {
            $pesertaList->where('peserta.nama', 'like', "%{$searchTerm}%");
        }

        // 🎯 Langkah 8: Lakukan paginasi dan kirim data ke view
        $pesertaList = $pesertaList->paginate(15)->withQueryString();

        // 🎯 Langkah 9: Kirim semua variabel yang dibutuhkan ke view
        return view('riwayat.peserta', compact('pesertaList', 'kegiatan'));
    }

    public function riwayatPanitia(Request $request)
    {
        $kegiatan = Kegiatan::orderBy('nama_kegiatan', 'asc')->get();
        $panitia = Panitia::orderBy('nama', 'asc')->get();
        $query = Absensi::with(['panitia.divisi', 'kegiatan'])->whereNotNull('panitia_id')->latest();

        if ($request->filled('kegiatan_id')) {
            $query->where('kegiatan_id', $request->kegiatan_id);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->whereHas('panitia', function ($q) use ($searchTerm) {
                $q->where('nama', 'like', "%{$searchTerm}%");
            });
        }

        $riwayat = $query->paginate(15)->withQueryString();

        return view('riwayat.panitia', compact('riwayat', 'kegiatan', 'panitia'));
    }

    public function storeManual(Request $request)
    {
        $validated = $request->validate([
            'kegiatan_id' => 'required|exists:kegiatan,id',
            'status' => 'required|in:izin,tidak_hadir',
            'tipe' => 'required|in:peserta,panitia',
            'peserta_id' => 'required_if:tipe,peserta|exists:peserta,id',
            'panitia_id' => 'required_if:tipe,panitia|exists:panitia,id',
        ]);

        $isDuplicate = Absensi::where('kegiatan_id', $request->kegiatan_id)
            ->where(function ($query) use ($request) {
                if ($request->tipe == 'peserta') {
                    $query->where('peserta_id', $request->peserta_id);
                } else {
                    $query->where('panitia_id', $request->panitia_id);
                }
            })->exists();

        if ($isDuplicate) {
            return back()->with('error', 'Gagal! Orang ini sudah terdata absensinya di kegiatan tersebut.');
        }

        $dataToSave = $validated;
        
        $dataToSave['user_id'] = Auth::id();
        $dataToSave['metode'] = 'manual';
        $dataToSave['waktu_hadir'] = now();

        unset($dataToSave['tipe']);

        Absensi::create($dataToSave);

        return back()->with('success', 'Absensi manual berhasil ditambahkan.');
    }

    public function exportPesertaExcel(Request $request)
    {
        return Excel::download(new PesertaAbsensiExport($request), 'riwayat_absensi_peserta.xlsx');
    }

    public function exportPanitiaExcel(Request $request)
    {
        return Excel::download(new PanitiaAbsensiExport($request), 'riwayat_absensi_panitia.xlsx');
    }

    public function update(Request $request, Absensi $absensi)
    {
        $validated = $request->validate([
            'status' => 'required|in:hadir,izin,tidak_hadir',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $absensi->update($validated);

        return back()->with('success', 'Riwayat absensi berhasil diperbarui.');
    }

    public function destroy(Absensi $absensi)
    {
        try {
            $absensi->delete();
            return back()->with('success', 'Riwayat absensi berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data.');
        }
    }
}