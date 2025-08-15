<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Prodi;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelasList = Kelas::with('prodi')->latest()->paginate(10);
        $prodis = Prodi::all();
        return view('kelas.index', compact('kelasList', 'prodis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $prodis = Prodi::orderBy('nama')->get(); // Ambil semua prodi untuk dropdown
        return view('kelas.create', compact('prodis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kelas,nama',
            'prodi_id' => 'required|exists:prodis,id',
        ]);

        Kelas::create($request->all());

        return back()->with('success', 'Kelas berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kelas $kelas)
    {
        return view('kelas.show', compact('kelas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kelas $kelas) // Sesuaikan nama variabel jika berbeda
    {
        $prodis = Prodi::orderBy('nama')->get(); // Ambil semua prodi untuk dropdown
        return view('kelas.edit', compact('kela', 'prodis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:kelas,nama,' . $kelas->id,
            'prodi_id' => 'required|exists:prodis,id',
        ]);

        $kelas->update($request->all());

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kelas $kelas)
    {
        $kelas->delete();
        return back()->with('success', 'Kelas berhasil dihapus.');
    }
}
