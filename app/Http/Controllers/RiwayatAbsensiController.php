<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Kegiatan;
use App\Models\Peserta;
use App\Models\Panitia;
use Illuminate\Support\Facades\Auth;

class RiwayatAbsensiController extends Controller
{
    public function riwayatPeserta(Request $request)
    {
        $kegiatan = Kegiatan::orderBy('nama_kegiatan', 'asc')->get();
        $peserta = Peserta::orderBy('nama', 'asc')->get();
        $query = Absensi::with(['peserta', 'kegiatan'])->whereNotNull('peserta_id')->latest();

        // --- PERBAIKAN DI SINI ---
        if ($request->filled('kegiatan_id')) {
            $query->where('kegiatan_id', $request->kegiatan_id); // Kembali menggunakan 'kegiatan_id'
        }
        // --- AKHIR PERBAIKAN ---

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->whereHas('peserta', function ($q) use ($searchTerm) {
                $q->where('nama', 'like', "%{$searchTerm}%");
            });
        }

        $riwayat = $query->paginate(15)->withQueryString();

        return view('riwayat.peserta', compact('riwayat', 'kegiatan', 'peserta'));
    }

    public function riwayatPanitia(Request $request)
    {
        $kegiatan = Kegiatan::orderBy('nama_kegiatan', 'asc')->get();
        $panitia = Panitia::orderBy('nama', 'asc')->get();
        $query = Absensi::with(['panitia.divisi', 'kegiatan'])->whereNotNull('panitia_id')->latest();

        // --- PERBAIKAN DI SINI ---
        if ($request->filled('kegiatan_id')) {
            $query->where('kegiatan_id', $request->kegiatan_id); // Kembali menggunakan 'kegiatan_id'
        }
        // --- AKHIR PERBAIKAN ---

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
        // Di view, nama inputnya adalah 'id_kegiatan', jadi kita ganti nama variabelnya agar tidak bingung
        $validated = $request->validate([
            'kegiatan_id' => 'required|exists:kegiatan,id', // Sesuaikan nama tabel ke 'kegiatan'
            'status' => 'required|in:izin,tidak_hadir',
            'tipe' => 'required|in:peserta,panitia',
            'peserta_id' => 'required_if:tipe,peserta|exists:peserta,id',
            'panitia_id' => 'required_if:tipe,panitia|exists:panitia,id',
        ]);

        // Cek duplikasi absensi
        $isDuplicate = Absensi::where('kegiatan_id', $request->id_kegiatan)
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
        
        // 2. Tambahkan field-field wajib lainnya
        $dataToSave['user_id'] = Auth::id();
        $dataToSave['metode'] = 'manual';
        $dataToSave['waktu_hadir'] = now();

        // Hapus 'tipe' karena tidak ada di tabel absensi
        unset($dataToSave['tipe']);

        Absensi::create($dataToSave);

        return back()->with('success', 'Absensi manual berhasil ditambahkan.');
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