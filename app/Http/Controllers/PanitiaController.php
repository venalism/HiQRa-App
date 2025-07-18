<?php

namespace App\Http\Controllers;

use App\Models\Panitia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PanitiaController extends Controller
{
    // Definisikan opsi
    private $jabatanOptions = [
        'BPH',
        'Multimedia',
        'Kaderisasi',
        'Akademisi',
        'Humas',
        'Pelatihan',
        'Minat Bakat'
    ];
    private $prodiOptions = ['D3', 'D4'];
    private $kelasOptions = [
        'D3 1A', 'D3 2A', 'D3 3A',
        'D4 1A', 'D4 1B', 'D4 1C', 'D4 2A', 'D4 2B', 'D4 3A', 'D4 3B'
    ];
    private $tingkatOptions = ['1', '2', '3', '4'];

    /**
     * Menampilkan daftar panitia.
     */
    public function index()
    {
        $panitia = Panitia::latest()->paginate(10);
        return view('panitia.index', compact('panitia'));
    }

    /**
     * Menampilkan form untuk menambahkan panitia baru.
     */
    public function create()
    {
        return view('panitia.create', [
            'jabatanOptions' => $this->jabatanOptions,
            'prodiOptions' => $this->prodiOptions,
            'kelasOptions' => $this->kelasOptions,
            'tingkatOptions' => $this->tingkatOptions,
        ]);
    }

    /**
     * Menyimpan data panitia baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:panitia',
            'no_hp' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:255',
            'prodi' => 'nullable|string|max:255',
            'kelas' => 'nullable|string|max:255',
            'tingkat' => 'nullable|string|max:255',
        ]);

        Panitia::create($request->all() + ['barcode' => (string) Str::uuid()]);

        return redirect()->route('panitia.index')
            ->with('success', 'Panitia berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit panitia.
     */
    public function edit(Panitia $panitia)
    {
        return view('panitia.edit', [
            'panitia' => $panitia,
            'jabatanOptions' => $this->jabatanOptions,
            'prodiOptions' => $this->prodiOptions,
            'kelasOptions' => $this->kelasOptions,
            'tingkatOptions' => $this->tingkatOptions,
        ]);
    }

    /**
     * Memperbarui data panitia.
     */
    public function update(Request $request, Panitia $panitia)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:panitia,email,' . $panitia->id,
            'no_hp' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:255',
            'prodi' => 'nullable|string|max:255',
            'kelas' => 'nullable|string|max:255',
            'tingkat' => 'nullable|string|max:255',
        ]);

        $panitia->update($request->all());

        return redirect()->route('panitia.index')
            ->with('success', 'Data panitia berhasil diperbarui.');
    }

    /**
     * Menghapus data panitia.
     */
    public function destroy(Panitia $panitia)
    {
        $panitia->delete();

        return redirect()->route('panitia.index')
            ->with('success', 'Panitia berhasil dihapus.');
    }

    /**
     * Menampilkan halaman QR Code panitia.
     */
    public function showQrCode(Panitia $panitia)
    {
        return view('panitia.qr', compact('panitia'));
    }
}
