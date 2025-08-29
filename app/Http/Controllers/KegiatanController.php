<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\Kelas;
use App\Models\Prodi;
use App\Models\Panitia;
use App\Models\Divisi;
use App\Models\Jabatan;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatans = Kegiatan::latest()->paginate(10);
        return view('kegiatan.index', compact('kegiatans'));
    }

    public function create()
    {
        $kelas = Kelas::with('prodi')->get();
        $panitias = Panitia::orderBy('nama')->get();
        $divisis = Divisi::orderBy('nama')->get();
        $jabatans = Jabatan::orderBy('nama')->get();

        return view('kegiatan.create', compact('kelas', 'panitias', 'divisis', 'jabatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'waktu' => 'required|date_format:H:i',
            'lokasi' => 'required|string|max:255',
            'kelas_id' => 'nullable|exists:kelas,id',
            'target_type' => 'nullable|in:panitia,divisi,jabatan',
        ]);

        $kegiatan = Kegiatan::create([
            'nama_kegiatan' => $request->nama_kegiatan,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'lokasi' => $request->lokasi,
            'kelas_id' => $request->kelas_id,
        ]);

        // Simpan target panitia
        if ($request->target_type === 'panitia' && $request->has('panitia_id')) {
            $kegiatan->panitias()->sync($request->panitia_id); // pastikan relasi many-to-many
        } elseif ($request->target_type === 'divisi') {
            $kegiatan->target_divisi_id = $request->divisi_id;
            $kegiatan->save();
        } elseif ($request->target_type === 'jabatan') {
            $kegiatan->target_jabatan_id = $request->jabatan_id;
            $kegiatan->save();
        }

        return redirect()->route('kegiatan.index')
                         ->with('success', 'Kegiatan baru berhasil ditambahkan.');
    }

    public function edit(Kegiatan $kegiatan)
    {
        $kelas = Kelas::with('prodi')->get();
        return view('kegiatan.edit', compact('kegiatan', 'kelas'));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'waktu' => 'required|date_format:H:i',
            'lokasi' => 'required|string|max:255',
            'kelas_id' => 'nullable|exists:kelas,id',
        ]);

        $kegiatan->update([
            'nama_kegiatan' => $request->nama_kegiatan,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'lokasi' => $request->lokasi,
            'kelas_id' => $request->kelas_id,
        ]);

        return redirect()->route('kegiatan.index')
                         ->with('success', 'Data kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();
        return redirect()->route('kegiatan.index')
                         ->with('success', 'Kegiatan berhasil dihapus.');
    }
}