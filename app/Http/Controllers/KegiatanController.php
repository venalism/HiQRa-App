<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kegiatans = Kegiatan::latest()->paginate(10);
        return view('kegiatan.index', compact('kegiatans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kegiatan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'waktu' => 'required|date_format:H:i',
            'lokasi' => 'required|string|max:255',
            'target_prodi' => 'nullable|string|max:255',
            'target_kelas' => 'nullable|string|max:255',
            'target_tingkat' => 'nullable|string|max:255',
            'target_jabatan' => 'nullable|string|max:255',
        ]);

        Kegiatan::create($request->all());

        return redirect()->route('kegiatan.index')
                         ->with('success', 'Kegiatan baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kegiatan $kegiatan)
    {
        return view('kegiatan.edit', compact('kegiatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kegiatan $kegiatan)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal' => 'required|date',
            'waktu' => 'required|date_format:H:i',
            'lokasi' => 'required|string|max:255',
            'target_prodi' => 'nullable|string|max:255',
            'target_kelas' => 'nullable|string|max:255',
            'target_tingkat' => 'nullable|string|max:255',
            'target_jabatan' => 'nullable|string|max:255',
        ]);

        $kegiatan->update($request->all());

        return redirect()->route('kegiatan.index')
                         ->with('success', 'Data kegiatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();

        return redirect()->route('kegiatan.index')
                         ->with('success', 'Kegiatan berhasil dihapus.');
    }
}
