@extends('layouts.app')

@section('title', 'Dashboard - Absensi QR')

@section('header')
    📋 Dashboard Absensi
@endsection

@section('content')

    <div class="animate-fadeIn">
        <div class="bg-white rounded-lg shadow-md card-simple p-6 mb-6">
            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">
                    Selamat Datang, <span class="text-red-gradient">{{ Auth::user()->name }}</span>
                </h2>
                <p class="text-gray-600">Kelola absensi kegiatan dengan mudah</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow-md card-simple p-6 text-center">
                <div class="w-16 h-16 red-gradient rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 16h4.01M20 12h.01M12 8h4.01M8 16h.01M4 16h.01M4 20h.01M12 20h.01M16 20h.01M20 20h.01M12 16h.01M8 12h.01M4 12h.01M4 8h.01M8 8h.01">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Scan QR Code</h3>
                <p class="text-gray-600 text-sm mb-4">Mulai proses absensi dengan memindai QR code</p>
                <a href="{{ route('absensi.scan') }}"
                    class="btn-hover inline-block w-full py-3 px-4 red-gradient text-white rounded-md font-medium">
                    Mulai Scan
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-md card-simple p-6 text-center">
                <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Kelola Peserta</h3>
                <p class="text-gray-600 text-sm mb-4">Tambah, edit, dan kelola data para peserta kegiatan</p>
                <a href="{{ route('peserta.index') }}"
                    class="btn-hover inline-block w-full py-3 px-4 bg-gray-800 text-white rounded-md font-medium hover:bg-gray-700">
                    Kelola Data
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-md card-simple p-6 text-center">
                <div class="w-16 h-16 red-gradient rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Kelola Panitia</h3>
                <p class="text-gray-600 text-sm mb-4">Tambah, edit, dan kelola data para panitia kegiatan</p>
                <a href="{{ route('panitia.index') }}"
                    class="btn-hover inline-block w-full py-3 px-4 red-gradient text-white rounded-md font-medium hover:bg-gray-700">
                    Kelola Data
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-md card-simple p-6 text-center">
                <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Kelola Kegiatan</h3>
                <p class="text-gray-600 text-sm mb-4">Buat, edit, dan atur semua acara yang akan datang</p>
                <a href="{{ route('kegiatan.index') }}"
                    class="btn-hover inline-block w-full py-3 px-4 bg-gray-800 text-white rounded-md font-medium hover:bg-gray-700">
                    Kelola Kegiatan
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mt-8">
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="text-2xl font-bold text-red-600">{{ $hadirHariIni }}</div>
                <div class="text-sm text-gray-600">Hadir Hari Ini</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="text-2xl font-bold text-gray-800">{{ $totalPeserta }}</div>
                <div class="text-sm text-gray-600">Total Peserta</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="text-2xl font-bold text-gray-800">{{ $totalPanitia }}</div>
                <div class="text-sm text-gray-600">Total Panitia</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="text-2xl font-bold text-gray-800">{{ $totalKegiatan }}</div>
                <div class="text-sm text-gray-600">Total Kegiatan</div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-4 text-center">
                <div class="text-2xl font-bold text-gray-600">{{ $belumHadir }}</div>
                <div class="text-sm text-gray-600">Belum Hadir</div>
            </div>
        </div>
    </div>
@endsection