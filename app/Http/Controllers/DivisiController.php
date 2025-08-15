<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect()->route('master.organisasi');
    }

    public function create()
    {
        $jabatans = Jabatan::orderBy('nama')->get();
        return view('divisi.create', compact('jabatans'));
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan_id' => 'required|exists:jabatans,id',
        ]);

        Divisi::create($request->all());
        return redirect()->route('master.organisasi')->with('success', 'Divisi baru berhasil ditambahkan.');
    }

    public function edit(Divisi $divisi)
    {
        $jabatans = Jabatan::orderBy('nama')->get();
        return view('divisi.edit', compact('divisi', 'jabatans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Divisi $divisi)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan_id' => 'required|exists:jabatans,id',
        ]);
        $divisi->update($request->all());

        return redirect()->route('master.organisasi')->with('success', 'Divisi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Divisi $divisi)
    {
        $divisi->delete();
        return redirect()->route('master.organisasi')->with('success', 'Divisi berhasil dihapus.');
    }
}