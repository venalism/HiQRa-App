<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
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
        return view('jabatan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:255|unique:jabatans,nama']);
        Jabatan::create($request->all());

        return redirect()->route('master.organisasi')->with('success', 'Jabatan baru berhasil ditambahkan.');
    }

    public function edit(Jabatan $jabatan)
    {
        return view('jabatan.edit', compact('jabatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jabatan $jabatan)
    {
        $request->validate(['nama' => 'required|string|max:255|unique:jabatans,nama,' . $jabatan->id]);
        $jabatan->update($request->all());

        return redirect()->route('master.organisasi')->with('success', 'Jabatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jabatan $jabatan)
    {
        $jabatan->delete();
        return redirect()->route('master.organisasi')->with('success', 'Jabatan berhasil dihapus.');
    }
}