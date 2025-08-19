<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use App\Models\Prodi;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Imports\PesertaWithRelationsImport;
use Maatwebsite\Excel\Facades\Excel;


class PesertaController extends Controller
{
    public function index(Request $request)
    {
        $prodis = Prodi::all();
        $kelas = Kelas::all();

        $query = Peserta::with(['kelas.prodi']);

        // Filter berdasarkan Prodi (via relasi kelas → prodi)
        if ($request->filled('prodi_id')) {
            $query->whereHas('kelas.prodi', function ($q) use ($request) {
                $q->where('id', $request->prodi_id);
            });
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

    public function edit($id)
    {
        $peserta = Peserta::findOrFail($id);
        $kelas = Kelas::all();
        return view('peserta.edit', compact('peserta', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:peserta,email',
            'npm' => 'required|string|unique:peserta,npm', // Tambahkan validasi
            'password' => 'required|string|min:8', // Tambahkan validasi
            'no_hp' => 'nullable|string',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        Peserta::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'npm' => $request->npm,
            'password' => Hash::make($request->password), // Hash password
            'no_hp' => $request->no_hp,
            'barcode' => Str::uuid(),
            'kelas_id' => $request->kelas_id,
        ]);

        return redirect()->route('peserta.index')->with('success', 'Peserta berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $peserta = Peserta::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:peserta,email,' . $id,
            'npm' => 'required|string|unique:peserta,npm,' . $id,
            'password' => 'nullable|string|min:8',
            'no_hp' => 'nullable|string',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $data = $request->except('password');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $peserta->update($data);

        return redirect()->route('peserta.index')->with('success', 'Peserta berhasil diperbarui.');
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
