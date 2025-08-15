<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use App\Models\Kelas;
use App\Models\Jabatan;
use App\Models\Divisi;

class MasterDataController extends Controller
{
    /**
     * Menampilkan halaman gabungan untuk Prodi dan Kelas.
     */
    public function akademik()
    {
        // Ambil data Prodi dengan paginasi
        $prodis = Prodi::latest()->paginate(10, ['*'], 'prodiPage');
        
        // Ambil data Kelas dengan paginasi
        $kelasList = Kelas::latest()->paginate(10, ['*'], 'kelasPage');
        
        // Ambil SEMUA prodi untuk dropdown di form Kelas
        $allProdis = Prodi::orderBy('nama')->get();

        return view('master.akademik', compact('prodis', 'kelasList', 'allProdis'));
    }

    /**
     * Menampilkan halaman gabungan untuk Jabatan dan Divisi.
     */
    public function organisasi()
    {
        $jabatans = Jabatan::latest()->paginate(10, ['*'], 'jabatanPage');
        $divisis = Divisi::latest()->paginate(10, ['*'], 'divisiPage');
        $allJabatans = Jabatan::orderBy('nama')->get();

        return view('master.organisasi', compact('jabatans', 'divisis', 'allJabatans'));
    }
}