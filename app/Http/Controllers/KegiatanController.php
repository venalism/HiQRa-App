<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kegiatan;
use App\Models\Kelas;
use App\Models\Prodi;

class KegiatanController extends Controller
{
    /**
     * Tampilkan daftar semua kegiatan.
     */
    public function index()
    {
        $kegiatans = Kegiatan::latest()->paginate(10);
        return view('kegiatan.index', compact('kegiatans'));
    }

    /**
     * Tampilkan formulir untuk membuat kegiatan baru.
     */
    public function create()
    {
        // Ambil semua data kelas dan muat relasi prodi-nya
        $kelas = Kelas::with('prodi')->get();
        return view('kegiatan.create', compact('kelas'));
    }

    /**
     * Simpan kegiatan yang baru dibuat ke database.
     */
    public function store(Request $request)
    {
        // Validasi input sesuai dengan field di form create.blade.php
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'waktu' => 'required|date_format:H:i',
            'lokasi' => 'required|string|max:255',
            'kelas_id' => 'nullable|exists:kelas,id', // Validasi untuk dropdown kelas
        ]);

        // Simpan data dengan aman
        Kegiatan::create([
            'nama_kegiatan' => $request->nama_kegiatan,
            'deskripsi' => $request->deskripsi,
            'tanggal' => $request->tanggal,
            'waktu' => $request->waktu,
            'lokasi' => $request->lokasi,
            'kelas_id' => $request->kelas_id,
        ]);

        return redirect()->route('kegiatan.index')
                         ->with('success', 'Kegiatan baru berhasil ditambahkan.');
    }
    
    /**
     * Tampilkan detail kegiatan.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Tampilkan form untuk mengedit kegiatan.
     */
    public function edit(Kegiatan $kegiatan)
    {
        // Tambahkan relasi prodi saat mengedit
        $kelas = Kelas::with('prodi')->get();
        return view('kegiatan.edit', compact('kegiatan', 'kelas'));
    }

    /**
     * Perbarui kegiatan di database.
     */
    public function update(Request $request, Kegiatan $kegiatan)
    {
        // Perbaiki validasi untuk metode update
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

    /**
     * Hapus kegiatan dari database.
     */
    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();

        return redirect()->route('kegiatan.index')
                         ->with('success', 'Kegiatan berhasil dihapus.');
    }
}
