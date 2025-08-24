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
    // Ambil semua kegiatan dan semua peserta untuk dropdown
    $kegiatan = Kegiatan::orderBy('nama_kegiatan', 'asc')->get();
    $semuaPeserta = Peserta::orderBy('nama')->get(); // <--- Ini untuk modal

    $kegiatanId = $request->input('kegiatan_id');
    $searchTerm = $request->input('search');

    // Defaultnya, buat koleksi kosong agar view tidak error
    $pesertaList = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);

    // Hanya jalankan query jika sebuah kegiatan sudah dipilih dari filter
    if ($kegiatanId) {
        $query = Peserta::query();
        
        // Filter peserta berdasarkan target kelas dari kegiatan yang dipilih
        $selectedKegiatan = Kegiatan::find($kegiatanId);
        if ($selectedKegiatan && $selectedKegiatan->kelas_id) {
            $query->where('peserta.kelas_id', $selectedKegiatan->kelas_id);
        }

        // Terapkan pencarian nama jika ada
        if ($searchTerm) {
            $query->where('peserta.nama', 'like', "%{$searchTerm}%");
        }

        // Lakukan join dengan tabel absensi
        $query->leftJoin('absensi', function($join) use ($kegiatanId) {
            $join->on('peserta.id', '=', 'absensi.peserta_id')
                 ->where('absensi.kegiatan_id', '=', $kegiatanId);
        });
        
        // --- PERBAIKAN UTAMA DI SINI ---
        // Pilih semua kolom yang kita butuhkan, termasuk 'waktu_hadir' dan 'absensi.id'
        $pesertaList = $query->select(
            'peserta.id as peserta_id', 
            'peserta.nama', 
            'peserta.npm', 
            'absensi.id as absensi_id',      // <-- PENTING untuk tombol Aksi
            'absensi.status',
            'absensi.waktu_hadir',           // <-- PENTING untuk kolom Waktu Absen
            'absensi.keterangan'
        )
        ->orderBy('peserta.nama', 'asc')
        ->paginate(15)
        ->withQueryString(); // Agar paginasi tetap membawa filter
    }

    // Kirim semua variabel yang dibutuhkan ke view
    return view('riwayat.peserta', compact('pesertaList', 'kegiatan', 'semuaPeserta'));
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
            'status' => 'required|in:hadir,izin,tidak_hadir',
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