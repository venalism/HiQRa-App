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
use App\Http\Controllers\ImportController;
use App\Http\Controllers\Auth\UserLoginController;
use App\Http\Controllers\Panitia\DashboardController as PanitiaDashboardController;
use App\Http\Controllers\Peserta\DashboardController as PesertaDashboardController;
use App\Http\Controllers\UserSettingsController;

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

// Rute untuk Panitia
Route::prefix('panitia')->group(function () {
    Route::get('/login', [UserLoginController::class, 'showPanitiaLoginForm'])->name('panitia.login');
    Route::post('/login', [UserLoginController::class, 'panitiaLogin']);

    Route::middleware('auth.multi:panitia')->group(function () {
        Route::get('/dashboard', [PanitiaDashboardController::class, 'index'])->name('panitia.dashboard');
        // Rute Settings Panitia
        Route::get('/settings', [UserSettingsController::class, 'showPanitiaSettingsForm'])->name('panitia.settings');
        Route::patch('/settings', [UserSettingsController::class, 'updatePanitia'])->name('panitia.settings.update');
    });
});

// Rute untuk Peserta
Route::prefix('peserta')->group(function () {
    Route::get('/login', [UserLoginController::class, 'showPesertaLoginForm'])->name('peserta.login');
    Route::post('/login', [UserLoginController::class, 'pesertaLogin']);

    Route::middleware('auth.multi:peserta')->group(function () {
        Route::get('/dashboard', [PesertaDashboardController::class, 'index'])->name('peserta.dashboard');
        // Rute Settings Peserta
        Route::get('/settings', [UserSettingsController::class, 'showPesertaSettingsForm'])->name('peserta.settings');
        Route::patch('/settings', [UserSettingsController::class, 'updatePeserta'])->name('peserta.settings.update');
    });
});

// Rute Logout (bisa diakses oleh panitia atau peserta)
Route::post('/logout', [UserLoginController::class, 'logout'])->name('user.logout');

// Admin Routes
Route::get('/admin', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin', [LoginController::class, 'login']);
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

// Publicly accessible QR Code for participants
    Route::get('/peserta/{peserta}/qr', [PesertaController::class, 'qr'])->name('peserta.qr');
    Route::get('/peserta/{peserta}/qr/download', [PesertaController::class, 'downloadQr'])->name('peserta.qr.download');
    Route::get('/panitia/{panitia}/qr', [PanitiaController::class, 'qr'])->name('panitia.qr');
    Route::get('/panitia/{panitia}/qr/download', [PanitiaController::class, 'downloadQr'])->name('panitia.qr.download');

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
        Route::get('/import', [ImportController::class, 'index'])->name('import.index');
        Route::post('/import', [ImportController::class, 'store'])->name('import.store');
        Route::get('/import/template/{type}', [ImportController::class, 'downloadTemplate'])->name('import.template');

        // CRUD Routes for Peserta
        Route::resource('peserta', PesertaController::class)->parameters([
            'peserta' => 'peserta'
        ]);
        Route::resource('panitia', PanitiaController::class)->parameters([
            'panitia' => 'panitia'
        ]);

        // CRUD Routes for Kegiatan
        Route::resource('kegiatan', KegiatanController::class);
        // Route untuk menampilkan halaman gabungan
        Route::get('/master/akademik', [MasterDataController::class, 'akademik'])->name('master.akademik');
        Route::get('/master/organisasi', [MasterDataController::class, 'organisasi'])->name('master.organisasi');

        // Route resource penuh untuk setiap model
        Route::resource('prodi', ProdiController::class);
        Route::resource('kelas', KelasController::class)->parameters([
            'kelas' => 'kelas'
        ]);;
        Route::resource('jabatan', JabatanController::class);
        Route::resource('divisi', DivisiController::class);
    });
});
