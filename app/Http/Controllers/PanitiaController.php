<?php

namespace App\Http\Controllers;

use App\Models\Panitia;
use App\Models\Divisi;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class PanitiaController extends Controller
{
    public function index()
    {
        $panitia = Panitia::with(['divisi.jabatan'])->paginate(10);
        return view('panitia.index', compact('panitia'));
    }

    public function create()
    {
        $divisi = Divisi::all();
        return view('panitia.create', compact('divisi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:panitia',
            'no_hp' => 'nullable|string|max:255',
            'divisi_id' => 'nullable|exists:divisis,id',
        ]);
        Panitia::create($request->all() + ['barcode' => (string) Str::uuid()]);
        return redirect()->route('panitia.index')->with('success', 'Data panitia berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $panitia = Panitia::findOrFail($id);
        $divisis = Divisi::all();
        return view('panitia.edit', compact('panitia', 'divisis'));
    }

    public function update(Request $request, $id)
    {
        $panitia = Panitia::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:panitia,email,' . $id,
            'no_hp' => 'nullable|string|max:255',
            'divisi_id' => 'nullable|exists:divisis,id',
        ]);

        $panitia->update($request->all());

        return redirect()->route('panitia.index')->with('success', 'Data panitia berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $panitia = Panitia::findOrFail($id);
        $panitia->delete();

        return redirect()->route('panitia.index')->with('success', 'Data panitia berhasil dihapus.');
    }

    public function qr($id)
    {
        $panitia = Panitia::findOrFail($id);

        // Logic untuk menampilkan halaman QR Code bisa ditambahkan di sini
        return view('panitia.qr', compact('panitia'));
    }
}
