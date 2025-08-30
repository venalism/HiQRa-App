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
        $kegiatans = Kegiatan::with('targetKelas')->latest()->paginate(10);
        return view('kegiatan.index', compact('kegiatans'));
    }

    public function create()
    {
        $kelas = Kelas::with('prodi')->get();
        $panitias = Panitia::orderBy('nama')->get();
        $divisis = Divisi::orderBy('nama')->get();
        //$jabatans = Jabatan::orderBy('nama')->get();

        return view('kegiatan.create', compact('kelas', 'panitias', 'divisis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'waktu' => 'required|date_format:H:i',
            'lokasi' => 'required|string|max:255',
            'kelas_id' => 'nullable|array',
            'kelas_id.*' => 'exists:kelas,id',
            'target_type' => 'nullable|in:panitia,divisi',
            'panitia_id' => 'nullable|array',
            'panitia_id.*' => 'exists:panitias,id',
        ]);

        $kegiatan = Kegiatan::create([
            'nama_kegiatan' => $request->nama_kegiatan,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'lokasi' => $request->lokasi,
        ]);

        // Simpan target panitia
        if ($request->target_type === 'panitia' && $request->has('panitia_id')) {
            $kegiatan->panitias()->sync($request->panitia_id);
        } elseif ($request->target_type === 'divisi' && $request->has('divisi_id')) {
            $kegiatan->targetDivisis()->sync($request->divisi_id);
        }
        // } elseif ($request->target_type === 'jabatan') {
        //     $kegiatan->target_jabatan_id = $request->jabatan_id;
        //     $kegiatan->save();
        // }

        if ($request->has('kelas_id')) {
            $kegiatan->targetKelas()->sync($request->kelas_id);
        }

        return redirect()->route('kegiatan.index')
                         ->with('success', 'Kegiatan baru berhasil ditambahkan.');
    }

    public function edit(Kegiatan $kegiatan)
    {
        $kelas = Kelas::with('prodi')->get();
        $divisis = Divisi::orderBy('nama')->get();
        $kegiatan->load('targetKelas');
        return view('kegiatan.edit', compact('kegiatan', 'kelas', 'divisis'));
    }

    public function update(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'waktu' => 'required|date_format:H:i',
            'lokasi' => 'required|string|max:255',
            'divisi_id' => 'nullable|array',
            'divisi_id.*' => 'exists:divisis,id',
            'kelas_id' => 'nullable|array',
            'kelas_id.*' => 'exists:kelas,id',
        ]);

        $kegiatan->update([
            'nama_kegiatan' => $request->nama_kegiatan,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'lokasi' => $request->lokasi,
        ]);

        //dd($request->kelas_id);
        if ($request->target_type === 'divisi' && $request->has('divisi_id')) {
            $kegiatan->targetDivisis()->sync($request->divisi_id);
        }

        if ($request->has('kelas_id')) {
           $kegiatan->targetKelas()->sync($request->input('kelas_id', []));
        }

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