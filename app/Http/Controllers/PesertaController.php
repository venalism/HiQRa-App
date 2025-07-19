<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class PesertaController extends Controller
{
    public function index()
    {
        $peserta = Peserta::with(['kelas.prodi'])->paginate(10);
        return view('peserta.index', compact('peserta'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        return view('peserta.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:peserta',
            'no_hp' => 'nullable|string|max:255',
            'kelas_id' => 'nullable|exists:kelas,id',
        ]);
        Peserta::create($request->all() + ['barcode' => (string) Str::uuid()]);
        return redirect()->route('peserta.index')->with('success', 'Data peserta berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $peserta = Peserta::findOrFail($id);
        $kelas = Kelas::all();
        return view('peserta.edit', compact('peserta', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $peserta = Peserta::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:peserta,email,' . $id,
            'no_hp' => 'nullable|string|max:255',
            'kelas_id' => 'nullable|exists:kelas,id',
        ]);

        $peserta->update($request->all());

        return redirect()->route('peserta.index')->with('success', 'Data peserta berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $peserta = Peserta::findOrFail($id);
        $peserta->delete();

        return redirect()->route('peserta.index')->with('success', 'Data peserta berhasil dihapus.');
    }

    public function qr($id)
    {
        $peserta = Peserta::findOrFail($id);

        // Logic untuk menampilkan halaman QR Code bisa ditambahkan di sini
        return view('peserta.qr', compact('peserta'));
    }
}
