<?php

namespace App\Http\Controllers;

use App\Models\Panitia;
use App\Models\Divisi;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Imports\PanitiaWithRelationsImport;
use Maatwebsite\Excel\Facades\Excel;


class PanitiaController extends Controller
{
    public function index(Request $request)
    {
        $jabatans = Jabatan::all();
        $divisis = Divisi::all();

        $query = Panitia::with(['divisi.jabatan']);

        // Filter berdasarkan Prodi (via relasi divisi → jabatan)
        if ($request->filled('jabatan_id')) {
            $query->whereHas('divisi.jabatan', function ($q) use ($request) {
                $q->where('id', $request->jabatan_id);
            });
        }

        // Filter berdasarkan Divisi
        if ($request->filled('divisi_id')) {
            $query->where('divisi_id', $request->divisi_id);
        }

        $panitia = $query->paginate(10);

        return view('panitia.index', compact('panitia', 'jabatans', 'divisis'));
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
             'no_hp' => 'required|string|max:15|unique:panitia,no_hp',
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
            'no_hp' => 'required|string|max:15|unique:panitia,no_hp,' . $id,
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

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new PanitiaWithRelationsImport, $request->file('file'));
            return back()->with('success', 'Data panitia berhasil diimpor!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            return back()->with('error', 'Gagal mengimpor data. Periksa kembali isi file Excel Anda.');
        }
    }
}
