<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PesertaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $peserta = Peserta::latest()->paginate(10);
        return view('peserta.index', compact('peserta'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('peserta.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:peserta',
            'no_hp' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:255',
        ]);

        Peserta::create($request->all() + ['barcode' => (string) Str::uuid()]);

        return redirect()->route('peserta.index')
                         ->with('success', 'Peserta berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Peserta $peserta)
    {
        return view('peserta.edit', compact('peserta'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Peserta $peserta)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:peserta,email,' . $peserta->id,
            'no_hp' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:255',
        ]);

        $peserta->update($request->all());

        return redirect()->route('peserta.index')
                         ->with('success', 'Data peserta berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Peserta $peserta)
    {
        $peserta->delete();

        return redirect()->route('peserta.index')
                         ->with('success', 'Peserta berhasil dihapus.');
    }
    /**
     * Menampilkan halaman QR Code untuk peserta tertentu.
     *
     * @param  \App\Models\Peserta  $peserta
     * @return \Illuminate\View\View
     */
    public function showQrCode(Peserta $peserta)
    {
        return view('peserta.qr', compact('peserta'));
    }
}
