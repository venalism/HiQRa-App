<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\PanitiaController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\RiwayatAbsensiController;
use App\Http\Controllers\MasterDataController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/guide', function () {
    return view('guide');
})->name('guide');

// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/riwayat-absensi/peserta', [RiwayatAbsensiController::class, 'riwayatPeserta'])->name('riwayat.peserta');
    Route::get('/riwayat-absensi/panitia', [RiwayatAbsensiController::class, 'riwayatPanitia'])->name('riwayat.panitia');
    Route::post('/riwayat-absensi/manual', [RiwayatAbsensiController::class, 'storeManual'])->name('riwayat.manual.store');
    Route::put('/riwayat-absensi/{absensi}', [RiwayatAbsensiController::class, 'update'])->name('riwayat.update');
    Route::delete('/riwayat-absensi/{absensi}', [RiwayatAbsensiController::class, 'destroy'])->name('riwayat.destroy');
    

    // Admin-only routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/scan', [AbsensiController::class, 'scan'])->name('absensi.scan');
        Route::post('/scan', [AbsensiController::class, 'store'])->name('absensi.store');
        Route::get('/riwayat-absensi/peserta/export', [RiwayatAbsensiController::class, 'exportPesertaExcel'])->name('riwayat.peserta.export');
        Route::get('/riwayat-absensi/panitia/export', [RiwayatAbsensiController::class, 'exportPanitiaExcel'])->name('riwayat.panitia.export');

        // CRUD Routes for Peserta
        Route::resource('peserta', PesertaController::class)->parameters([
            'peserta' => 'peserta'
        ]);
        Route::resource('panitia', PanitiaController::class)->parameters([
            'panitia' => 'panitia'
        ]);

        // Route untuk menampilkan halaman gabungan
        Route::get('/master/akademik', [MasterDataController::class, 'akademik'])->name('master.akademik');
        Route::get('/master/organisasi', [MasterDataController::class, 'organisasi'])->name('master.organisasi');

        // Route resource penuh untuk setiap model
        Route::resource('prodi', ProdiController::class);
        Route::resource('kelas', KelasController::class);
        Route::resource('jabatan', JabatanController::class);
        Route::resource('divisi', DivisiController::class);
    });

    // Publicly accessible QR Code for participants
    Route::get('/peserta/{peserta}/qr', [PesertaController::class, 'qr'])->name('peserta.qr');
    Route::get('/panitia/{panitia}/qr', [PanitiaController::class, 'qr'])->name('panitia.qr');
});
