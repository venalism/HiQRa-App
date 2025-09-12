<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Kegiatan;
use App\Models\Peserta;
use App\Models\Panitia;
use App\Models\Jabatan;
use App\Models\Divisi;
use App\Models\Prodi;
use App\Models\Kelas;
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
        // Dropdown: kegiatan, peserta, prodi, kelas
        $kegiatan = Kegiatan::orderBy('nama_kegiatan', 'asc')->get();
        $semuaPeserta = Peserta::orderBy('nama')->get(); // untuk modal
        $prodis = Prodi::all();
        $kelas = Kelas::all();
        $selectedKegiatan = null;

        $kegiatanId = $request->input('kegiatan_id');
        $searchTerm = $request->input('search');

        $riwayat = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);

        if ($kegiatanId) {
            $query = Peserta::with(['kelas.prodi']);

            // Filter target kelas dari kegiatan
            $selectedKegiatan = Kegiatan::with('targetKelas')->find($kegiatanId);
            if ($selectedKegiatan && $selectedKegiatan->targetKelas()->exists()) {
                $query->whereIn('peserta.kelas_id', $selectedKegiatan->targetKelas->pluck('id'));
            }

            // 🔎 Filter Prodi
            if ($request->filled('prodi_id')) {
                $query->whereHas('kelas.prodi', function ($q) use ($request) {
                    $q->where('id', $request->prodi_id);
                });
            }

            // 🔎 Filter Kelas
            if ($request->filled('kelas_id')) {
                $query->where('kelas_id', $request->kelas_id);
            }

            // 🔎 Pencarian nama
            if ($searchTerm) {
                $query->where('peserta.nama', 'like', "%{$searchTerm}%");
            }

            // Join ke absensi
            $query->leftJoin('absensi', function ($join) use ($kegiatanId) {
                $join->on('peserta.id', '=', 'absensi.peserta_id')
                    ->where('absensi.kegiatan_id', '=', $kegiatanId);
            });

            $riwayat = $query->select(
                'peserta.id as peserta_id',
                'peserta.nama',
                'peserta.npm',
                'absensi.id as absensi_id',
                DB::raw("COALESCE(absensi.status, 'tidak_hadir') as status"),
                'absensi.waktu_hadir',
                'absensi.keterangan',
                'absensi.file_surat'
            )
                ->orderBy('peserta.nama', 'asc')
                ->paginate(15)
                ->withQueryString();
        }

        return view('riwayat.peserta', compact(
            'riwayat',
            'kegiatan',
            'semuaPeserta',
            'selectedKegiatan',
            'prodis',
            'kelas'
        ));
    }

    public function riwayatPanitia(Request $request)
    {
        $kegiatan = Kegiatan::orderBy('nama_kegiatan', 'asc')->get();
        $panitia = Panitia::orderBy('nama', 'asc')->get();

        // 🔎 Tambahan untuk dropdown filter
        $jabatans = Jabatan::all();
        $divisis = Divisi::all();

        $selectedKegiatan = null;
        $riwayat = new LengthAwarePaginator([], 0, 15);

        if ($request->filled('kegiatan_id')) {
            $selectedKegiatan = Kegiatan::with(['targetDivisis', 'panitias'])->find($request->kegiatan_id);

            // Ambil semua panitia yang jadi target kegiatan
            $query = Panitia::with(['divisi.jabatan']);

            $divisiIds = $selectedKegiatan->targetDivisis->pluck('id')->toArray();
            $panitiaIds = $selectedKegiatan->panitias->pluck('id')->toArray();

            $query->where(function ($q) use ($divisiIds, $panitiaIds) {
                $q->whereIn('divisi_id', $divisiIds)
                    ->orWhereIn('id', $panitiaIds);
            });

            // 🔎 Filter Nama
            if ($request->filled('search')) {
                $query->where('nama', 'like', "%{$request->search}%");
            }

            // 🔎 Filter Jabatan
            if ($request->filled('jabatan_id')) {
                $query->whereHas('divisi.jabatan', function ($q) use ($request) {
                    $q->where('id', $request->jabatan_id);
                });
            }

            // 🔎 Filter Divisi
            if ($request->filled('divisi_id')) {
                $query->where('divisi_id', $request->divisi_id);
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
            )
                ->orderBy('panitia.nama', 'asc')
                ->paginate(15)
                ->withQueryString();
        }

        return view('riwayat.panitia', compact(
            'riwayat',
            'kegiatan',
            'panitia',
            'jabatans',   // ⬅️ kirim ke view
            'divisis'     // ⬅️ kirim ke view
        ));
    }


    public function storeManual(Request $request)
    {
        $validated = $request->validate([
            'kegiatan_id' => 'required|exists:kegiatan,id',
            'status' => 'required|in:hadir,izin,tidak_hadir,sakit',
            'tipe' => 'required|in:peserta,panitia',
            'peserta_id' => 'required_if:tipe,peserta|exists:peserta,id',
            'panitia_id' => 'required_if:tipe,panitia|exists:panitia,id',
            'keterangan' => 'nullable|string|max:255',
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
            'waktu_hadir' => $validated['status'] === 'hadir' ? now() : null,
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
