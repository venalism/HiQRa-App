<?php

namespace App\Http\Controllers;

use App\Models\Panitia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PanitiaController extends Controller
{
    // Definisikan jabatan panitia di sini
    private $jabatanOptions = [
        'BPH', 'Multimedia', 'Kaderisasi', 'Akademisi', 'Humas', 'Pelatihan', 'Minat Bakat'
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $panitia = Panitia::latest()->paginate(10);
        return view('panitia.index', compact('panitia'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Kirim opsi jabatan ke view
        return view('panitia.create', ['jabatanOptions' => $this->jabatanOptions]);
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

        Panitia::create($request->all() + ['barcode' => (string) Str::uuid()]);

        return redirect()->route('panitia.index')
                         ->with('success', 'panitia berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Panitia $panitia)
    {
        // Kirim opsi jabatan dan data panitia ke view
        return view('panitia.edit', [
            'panitia' => $panitia,
            'jabatanOptions' => $this->jabatanOptions
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Panitia $panitia)
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
    public function destroy(Panitia $panitia)
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
    public function showQrCode(Panitia $panitia)
    {
        return view('panitia.qr', compact('panitia'));
    }
}