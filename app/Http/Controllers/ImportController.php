<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PesertaWithRelationsImport;
use App\Imports\PanitiaWithRelationsImport;
use App\Exports\PesertaTemplateExport;
use App\Exports\PanitiaTemplateExport;

class ImportController extends Controller
{
    /**
     * Menampilkan halaman utama untuk import.
     */
    public function index()
    {
        return view('import.index');
    }

    /**
     * Memproses file Excel yang diunggah.
     */
    public function store(Request $request)
    {
        $request->validate([
            'import_type' => 'required|string|in:peserta,panitia',
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $type = $request->input('import_type');
        $file = $request->file('file');

        $importClass = ($type === 'peserta') ? new PesertaWithRelationsImport : new PanitiaWithRelationsImport;

        try {
            Excel::import($importClass, $file);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];
            foreach ($failures as $failure) {
                $errorMessages[] = 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            return back()->withErrors($errorMessages)->withInput();
        } catch (\Exception $e) {
            // Menampilkan pesan error yang lebih spesifik jika terjadi
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        return back()->with('success', 'Data ' . ucfirst($type) . ' berhasil diimpor!');
    }

    /**
     * Mengunduh file template Excel.
     */
    public function downloadTemplate(string $type)
    {
        if ($type === 'peserta') {
            return Excel::download(new PesertaTemplateExport, 'template_peserta.xlsx');
        }

        if ($type === 'panitia') {
            return Excel::download(new PanitiaTemplateExport, 'template_panitia.xlsx');
        }

        return redirect()->route('import.index')->with('error', 'Jenis template tidak valid.');
    }
}