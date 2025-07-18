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
        $divisis = Divisi::with('jabatan')->latest()->paginate(10);
        $jabatans = Jabatan::all();
        return view('divisi.index', compact('divisis', 'jabatans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:divisis,nama',
            'jabatan_id' => 'required|exists:jabatans,id',
        ]);

        Divisi::create($request->all());

        return back()->with('success', 'Divisi berhasil ditambahkan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Divisi $divisi)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:divisis,nama,' . $divisi->id,
            'jabatan_id' => 'required|exists:jabatans,id',
        ]);

        $divisi->update($request->all());

        return redirect()->route('divisi.index')->with('success', 'Divisi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Divisi $divisi)
    {
        $divisi->delete();
        return back()->with('success', 'Divisi berhasil dihapus.');
    }
}