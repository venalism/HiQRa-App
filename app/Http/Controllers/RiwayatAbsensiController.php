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
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class RiwayatAbsensiController extends Controller
{
    public function riwayatPeserta(Request $request)
{
    // Ambil semua kegiatan dan semua peserta untuk dropdown
    $kegiatan = Kegiatan::orderBy('nama_kegiatan', 'asc')->get();
    $semuaPeserta = Peserta::orderBy('nama')->get(); // <--- Ini untuk modal
    $selectedKegiatan = null;

    $kegiatanId = $request->input('kegiatan_id');
    $searchTerm = $request->input('search');

    // Defaultnya, buat koleksi kosong agar view tidak error
    $riwayat = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);

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
        $riwayat = $query->select(
            'peserta.id as peserta_id', 
            'peserta.nama', 
            'peserta.npm', 
            'absensi.id as absensi_id',      // <-- PENTING untuk tombol Aksi
             DB::raw("COALESCE(absensi.status, 'tidak_hadir') as status"),
            'absensi.waktu_hadir',           // <-- PENTING untuk kolom Waktu Absen
            'absensi.keterangan',
            'absensi.file_surat'
        )
        ->orderBy('peserta.nama', 'asc')
        ->paginate(15)
        ->withQueryString(); // Agar paginasi tetap membawa filter
    }

    // Kirim semua variabel yang dibutuhkan ke view
    return view('riwayat.peserta', compact('riwayat', 'kegiatan', 'semuaPeserta', 'selectedKegiatan'));
}

    public function riwayatPanitia(Request $request)
    {
        $kegiatan = Kegiatan::orderBy('nama_kegiatan', 'asc')->get();
        $panitia = Panitia::orderBy('nama', 'asc')->get();
        $selectedKegiatan = null;
        $riwayat = new LengthAwarePaginator([], 0, 15);

        if ($request->filled('kegiatan_id')) {
            $selectedKegiatan = Kegiatan::find($request->kegiatan_id);

            // Ambil semua panitia yang jadi target kegiatan
            $query = Panitia::query();

            if ($selectedKegiatan->target_divisi_id) {
                $query->where('divisi_id', $selectedKegiatan->target_divisi_id);
            }

            if ($selectedKegiatan->panitias()->exists()) {
                $query->orWhereIn('id', $selectedKegiatan->panitias->pluck('id'));
            }

            if ($request->filled('search')) {
                $query->where('nama', 'like', "%{$request->search}%");
            }

            // Join dengan absensi
            $query->leftJoin('absensi', function ($join) use ($request) {
                $join->on('panitia.id', '=', 'absensi.panitia_id')
                    ->where('absensi.kegiatan_id', '=', $request->kegiatan_id);
            });

            $riwayat = $query->select(
                'panitia.id as panitia_id',
                'panitia.nama',
                'panitia.npm',
                'panitia.divisi_id',
                'absensi.id as absensi_id',
                DB::raw("COALESCE(absensi.status, 'tidak_hadir') as status"),
                'absensi.waktu_hadir',
                'absensi.keterangan',
                'absensi.file_surat'
            )->orderBy('panitia.nama', 'asc')->paginate(15)->withQueryString();
        }

        return view('riwayat.panitia', compact('riwayat', 'kegiatan', 'panitia'));
    }

    public function storeManual(Request $request)
    {
        $validated = $request->validate([
            'kegiatan_id' => 'required|exists:kegiatan,id',
            'status' => 'required|in:hadir,izin,tidak_hadir,sakit',
            'tipe' => 'required|in:peserta,panitia',
            'peserta_id' => 'required_if:tipe,peserta|exists:peserta,id',
            'panitia_id' => 'required_if:tipe,panitia|exists:panitia,id',
            'keterangan' => 'required|string|max:255',
            'file_surat' => 'nullable|mimes:pdf|max:2048',
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
            'status' => $validated['status'],
            'keterangan' => $validated['keterangan'],
        ];

        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('surat', $filename, 'public');
            $dataToSave['file_surat'] = $path;
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
            'status' => 'required|in:hadir,izin,tidak_hadir,sakit',
            'keterangan' => 'nullable|string|max:255',
            'file_surat' => 'nullable|mimes:pdf|max:2048',
        ]);

        $dataToUpdate = [];

        if ($validated['status'] == 'hadir') {
            $dataToUpdate['status'] = $validated['status'];
            $dataToUpdate['waktu_hadir'] = $absensi->waktu_hadir ?? now();
            $dataToUpdate['keterangan'] = $validated['keterangan'];
        } elseif ($validated['status'] == 'izin') {
            $dataToUpdate['status'] = $validated['status'];
            $dataToUpdate['waktu_hadir'] = null;
            $dataToUpdate['keterangan'] = $validated['keterangan']; // isi sesuai input user
        } elseif ($validated['status'] == 'sakit') {
            $dataToUpdate['status'] = $validated['status'];
            $dataToUpdate['waktu_hadir'] = null;
            $dataToUpdate['keterangan'] = $validated['keterangan']; // isi sesuai input user
        } elseif ($validated['status'] == 'tidak_hadir') {
            $dataToUpdate['status'] = $validated['status'];
            $dataToUpdate['waktu_hadir'] = null;
            $dataToUpdate['keterangan'] = 'tidak melakukan absensi'; // isi sesuai input user
        }

        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('surat', $filename, 'public');
            $dataToUpdate['file_surat'] = $path; // masukkan ke array update
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