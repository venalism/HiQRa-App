<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use App\Models\Prodi;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Imports\PesertaWithRelationsImport;
use Maatwebsite\Excel\Facades\Excel;


class PesertaController extends Controller
{
    public function index(Request $request)
    {
        $prodis = Prodi::all();
        $kelas = Kelas::all();

        $query = Peserta::with(['prodi', 'kelas']);

        // Filter berdasarkan Prodi
        if ($request->filled('prodi_id')) {
            $query->where('prodi_id', $request->prodi_id);
        }

        // Filter berdasarkan Kelas
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        $peserta = $query->paginate(10);

        return view('peserta.index', compact('peserta', 'prodis', 'kelas'));
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
            'no_hp' => 'required|string|max:15|unique:peserta,no_hp',
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
            'no_hp' => 'required|string|max:15|unique:peserta,no_hp,' . $id,
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

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new PesertaWithRelationsImport, $request->file('file'));
            return back()->with('success', 'Data peserta berhasil diimpor!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            // Tangani error validasi dari file Excel
            return back()->with('error', 'Gagal mengimpor data. Periksa kembali isi file Excel Anda.');
        }
    }
}
