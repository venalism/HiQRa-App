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
        // Ambil data untuk dropdown filter
        $kegiatan = Kegiatan::orderBy('nama_kegiatan', 'asc')->get();
        $semuaPeserta = Peserta::orderBy('nama')->get(); // Untuk modal tambah manual

        // Ambil input dari filter
        $kegiatanId = $request->input('kegiatan_id');
        $searchTerm = $request->input('search');

        // Mulai query dari model Absensi, sama seperti riwayatPanitia
        $query = Absensi::with(['peserta.kelas.prodi', 'kegiatan'])
                        ->whereNotNull('peserta_id'); // Pastikan hanya absensi peserta

        // Terapkan filter jika ada
        if ($kegiatanId) {
            $query->where('kegiatan_id', $kegiatanId);
        }

        if ($searchTerm) {
            $query->whereHas('peserta', function ($q) use ($searchTerm) {
                $q->where('nama', 'like', "%{$searchTerm}%");
            });
        }

        // Ambil data dengan paginasi
        $riwayat = $query->latest()->paginate(15)->withQueryString();

        return view('riwayat.peserta', [
            'riwayat' => $riwayat, // Ganti nama variabel agar konsisten
            'kegiatan' => $kegiatan,
            'semuaPeserta' => $semuaPeserta,
        ]);
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

       $dataToSave = [
            'kegiatan_id' => $validated['kegiatan_id'],
            'peserta_id' => $validated['peserta_id'] ?? null,
            'panitia_id' => $validated['panitia_id'] ?? null,
            'user_id' => Auth::id(),
            'metode' => 'manual',
            'waktu_hadir' => null, // Default null
            'keterangan' => null, // Default null
        ];

        if ($validated['status'] == 'hadir') {
            $dataToSave['waktu_hadir'] = now();
        } elseif ($validated['status'] == 'izin') {
            $dataToSave['keterangan'] = 'Izin';
        } elseif ($validated['status'] == 'tidak_hadir') {
            $dataToSave['keterangan'] = 'Sakit';
        }

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

        $dataToUpdate = [];

        if ($validated['status'] == 'hadir') {
            $dataToUpdate['waktu_hadir'] = $absensi->waktu_hadir ?? now(); // Isi jika kosong, atau biarkan jika sudah ada
            $dataToUpdate['keterangan'] = null;
        } elseif ($validated['status'] == 'izin') {
            $dataToUpdate['waktu_hadir'] = null;
            $dataToUpdate['keterangan'] = 'Izin';
        } elseif ($validated['status'] == 'tidak_hadir') {
            $dataToUpdate['waktu_hadir'] = null;
            $dataToUpdate['keterangan'] = 'Sakit';
        }

        $absensi->update($dataToUpdate);

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