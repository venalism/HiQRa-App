<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\PanitiaController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\DashboardController;

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

    Route::get('/scan', [AbsensiController::class, 'scan'])->name('absensi.scan');
    Route::post('/scan/store', [AbsensiController::class, 'store'])->name('absensi.store');

    // CRUD Routes for Peserta
    Route::resource('peserta', PesertaController::class);
    Route::resource('panitia', PanitiaController::class);
});

// Publicly accessible QR Code for participants
Route::get('/peserta/{peserta}/qr', [PesertaController::class, 'showQrCode'])->name('peserta.qr');
Route::get('/panitia/{panitia}/qr', [PanitiaController::class, 'showQrCode'])->name('panitia.qr');

// CRUD Routes for Kegiatan
Route::resource('kegiatan', KegiatanController::class);

