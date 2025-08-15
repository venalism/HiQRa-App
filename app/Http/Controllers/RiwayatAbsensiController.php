<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Kegiatan;
use App\Models\Peserta;
use App\Models\Panitia;

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
            'kegiatan_id' => 'required|exists:kegiatan,id',
            'status' => 'required|in:izin,tidak_hadir',
            'tipe' => 'required|in:peserta,panitia',
            'peserta_id' => 'required_if:tipe,peserta|exists:peserta,id',
            'panitia_id' => 'required_if:tipe,panitia|exists:panitia,id',
        ]);

        // Cek duplikasi absensi
        // --- PERBAIKAN DI SINI ---
        $isDuplicate = Absensi::where('kegiatan_id', $request->id_kegiatan) // Menggunakan 'kegiatan_id'
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

        // Simpan data
        Absensi::create([
            'id_kegiatan' => $validated['kegiatan_id'], // Mapping dari input form ke kolom database
            'status' => $validated['status'],
            'peserta_id' => $validated['peserta_id'] ?? null,
            'panitia_id' => $validated['panitia_id'] ?? null,
        ]);
        // --- AKHIR PERBAIKAN ---

        return back()->with('success', 'Absensi manual berhasil ditambahkan.');
    }
}