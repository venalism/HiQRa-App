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

// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin-only routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/scan', [AbsensiController::class, 'scan'])->name('absensi.scan');
        Route::post('/scan', [AbsensiController::class, 'store'])->name('absensi.store');

        // CRUD Routes for Peserta
        Route::resource('peserta', PesertaController::class)->parameters([
            'peserta' => 'peserta'
        ]);
        Route::resource('panitia', PanitiaController::class)->parameters([
            'panitia' => 'panitia'
        ]);

        // CRUD Routes for Kegiatan
        Route::resource('kegiatan', KegiatanController::class);
        // CRUD Routes for Jabatan
        Route::resource('jabatan', JabatanController::class)->except(['create', 'show', 'edit']);
        // CRUD Routes for Divisi
        Route::resource('divisi', DivisiController::class)->except(['create', 'show', 'edit']);
        // CRUD Routes for Prodi
        Route::resource('prodi', ProdiController::class)->except(['create', 'show', 'edit']);
        Route::resource('kelas', KelasController::class)->except(['create', 'show', 'edit']);
    });

    // Publicly accessible QR Code for participants
    Route::get('/peserta/{peserta}/qr', [PesertaController::class, 'qr'])->name('peserta.qr');
    Route::get('/panitia/{panitia}/qr', [PanitiaController::class, 'qr'])->name('panitia.qr');
});
