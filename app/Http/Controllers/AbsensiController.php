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
    /**
     * Tampilkan riwayat absensi untuk peserta.
     */
    public function riwayatPeserta(Request $request)
    {
        // Ambil data kegiatan untuk dropdown filter
        $kegiatan = Kegiatan::orderBy('nama_kegiatan', 'asc')->get();
        $kegiatanId = $request->input('kegiatan_id');
        $searchTerm = $request->input('search');

        // Mulai query dari tabel Peserta.
        // Kita ingin menampilkan semua peserta, terlepas dari apakah mereka sudah diabsen atau belum.
        $query = Peserta::query();

        // Gabungkan (join) dengan tabel absensi, ini penting untuk mendapatkan status kehadiran.
        // 'leftJoin' akan tetap menampilkan peserta meskipun mereka tidak punya data di tabel absensi.
        $query->leftJoin('absensi', function ($join) use ($kegiatanId) {
            $join->on('peserta.id', '=', 'absensi.peserta_id')
                ->where('absensi.kegiatan_id', '=', $kegiatanId);
        });

        // Terkadang ada kegiatan yang menargetkan kelas atau prodi tertentu.
        if ($kegiatanId) {
            $selectedKegiatan = Kegiatan::with(['kelas', 'kelas.prodi'])->find($kegiatanId);
            
            // Jika kegiatan memiliki target kelas
            if ($selectedKegiatan->kelas) {
                $query->where('peserta.kelas_id', $selectedKegiatan->kelas->id);
            }
        }
        
        // Pilih kolom yang dibutuhkan dan tambahkan status
        $riwayat = $query->select(
            'peserta.id as peserta_id', // Beri alias agar tidak bentrok
            'peserta.nama',
            'peserta.npm',
            'absensi.status',
            'absensi.keterangan'
        )
        ->orderBy('peserta.nama', 'asc');

        // Terapkan filter pencarian nama jika ada
        if ($searchTerm) {
            $riwayat->where('peserta.nama', 'like', "%{$searchTerm}%");
        }

        // Lakukan paginasi dan kirim data ke view
        $riwayat = $riwayat->paginate(15)->withQueryString();

        // Kirim semua variabel yang dibutuhkan ke view
        return view('riwayat.peserta', compact('riwayat', 'kegiatan'));
    }

    /**
     * Tampilkan riwayat absensi untuk panitia.
     */
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

    /**
     * Simpan absensi manual.
     */
    public function storeManual(Request $request)
    {
        $validated = $request->validate([
            'kegiatan_id' => 'required|exists:kegiatan,id',
            'status' => 'required|in:izin,tidak_hadir',
            'tipe' => 'required|in:peserta,panitia',
            'peserta_id' => 'required_if:tipe,peserta|exists:peserta,id',
            'panitia_id' => 'required_if:tipe,panitia|exists:panitia,id',
        ]);
    
        // Cek duplikasi absensi
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
            'kegiatan_id' => $request->kegiatan_id,
            'status' => $request->status,
            'user_id' => Auth::id(),
            'metode' => 'manual',
            'waktu_hadir' => now(),
        ];
    
        if ($request->tipe == 'peserta') {
            $dataToSave['peserta_id'] = $request->peserta_id;
        } else {
            $dataToSave['panitia_id'] = $request->panitia_id;
        }

        Absensi::create($dataToSave);
    
        return back()->with('success', 'Absensi manual berhasil ditambahkan.');
    }

    /**
     * Ekspor data absensi peserta ke Excel.
     */
    public function exportPesertaExcel(Request $request)
    {
        return Excel::download(new PesertaAbsensiExport($request), 'riwayat_absensi_peserta.xlsx');
    }

    /**
     * Ekspor data absensi panitia ke Excel.
     */
    public function exportPanitiaExcel(Request $request)
    {
        return Excel::download(new PanitiaAbsensiExport($request), 'riwayat_absensi_panitia.xlsx');
    }

    /**
     * Perbarui data absensi.
     */
    public function update(Request $request, Absensi $absensi)
    {
        $validated = $request->validate([
            'status' => 'required|in:hadir,izin,tidak_hadir',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $absensi->update($validated);

        return back()->with('success', 'Riwayat absensi berhasil diperbarui.');
    }

    /**
     * Hapus data absensi.
     */
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
