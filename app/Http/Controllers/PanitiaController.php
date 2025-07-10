<?php

namespace App\Http\Controllers;

use App\Models\Panitia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class panitiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $panitia = panitia::latest()->paginate(10);
        return view('panitia.index', compact('panitia'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('panitia.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:panitia',
            'no_hp' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:255',
        ]);

        panitia::create($request->all() + ['barcode' => (string) Str::uuid()]);

        return redirect()->route('panitia.index')
                         ->with('success', 'panitia berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(panitia $panitia)
    {
        return view('panitia.edit', compact('panitia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, panitia $panitia)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:panitia,email,' . $panitia->id,
            'no_hp' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:255',
        ]);

        $panitia->update($request->all());

        return redirect()->route('panitia.index')
                         ->with('success', 'Data panitia berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(panitia $panitia)
    {
        $panitia->delete();

        return redirect()->route('panitia.index')
                         ->with('success', 'panitia berhasil dihapus.');
    }
    /**
     * Menampilkan halaman QR Code untuk panitia tertentu.
     *
     * @param  \App\Models\panitia  $panitia
     * @return \Illuminate\View\View
     */
    public function showQrCode(panitia $panitia)
    {
        return view('panitia.qr', compact('panitia'));
    }
}
