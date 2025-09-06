@extends('layouts.user_app')

@section('header')
    📋 Dashboard Peserta
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Grid utama responsif --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

                {{-- GRID 1: QR CODE & Info --}}
                <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-md flex flex-col items-center">
                    <h3 class="text-xl font-bold mb-4">QR Code Absensi</h3>
                    <div class="p-4 bg-gray-100 rounded-lg">
                        {!! QrCode::size(250)->generate($peserta->barcode) !!}
                    </div>
                    <p class="mt-4 text-sm text-gray-600">Tunjukkan kode ini untuk absensi</p>
                    <p class="text-lg font-bold text-gray-800 mt-2">{{ $peserta->nama }}</p>
                    <p class="text-sm text-gray-500">{{ $peserta->npm }}</p>
                    <div class="mt-4">
                        <a href="{{ route('peserta.qr.download', $peserta->id) }}"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow">
                            Download QR Code
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-3 flex flex-col gap-6">
                    {{-- GRID 2: Statistik Kehadiran --}}
                    <div class="bg-white p-6 rounded-2xl shadow-md">
                        <h3 class="text-xl font-bold mb-4">Statistik Kehadiran</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 text-center">
                            <div class="bg-blue-100 p-4 rounded-md flex flex-col justify-center">
                                <p class="text-3xl font-bold text-blue-800">{{ $hadirPercentage }}%</p>
                                <p class="text-sm text-blue-600">Overall</p>
                            </div>
                            <div class="bg-green-100 p-4 rounded-md flex flex-col justify-center">
                                <p class="text-2xl font-bold text-green-800">{{ $hadirCount }}</p>
                                <p class="text-sm text-green-600">Hadir</p>
                            </div>
                            <div class="bg-yellow-100 p-4 rounded-md flex flex-col justify-center">
                                <p class="text-2xl font-bold text-yellow-800">{{ $izinCount }}</p>
                                <p class="text-sm text-yellow-600">Izin</p>
                            </div>
                            <div class="bg-red-100 p-4 rounded-md flex flex-col justify-center">
                                <p class="text-2xl font-bold text-red-800">{{ $sakitCount }}</p>
                                <p class="text-sm text-red-600">Sakit</p>
                            </div>
                            <div class="bg-gray-100 p-4 rounded-md flex flex-col justify-center">
                                <p class="text-2xl font-bold text-gray-800">{{ $absenCount }}</p>
                                <p class="text-sm text-gray-600">Absen</p>
                            </div>
                        </div>


                        <h3 class="text-lg font-bold mt-8 mb-4">Riwayat Kehadiran Terakhir</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Kegiatan</th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Tanggal</th>
                                        <th class="px-4 py-2 text-left font-medium text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach ($riwayatAbsensi as $kegiatan)
                                        <tr>
                                            <td class="px-4 py-2">{{ $kegiatan->nama_kegiatan }}</td>
                                            <td class="px-4 py-2">
                                                {{ \Carbon\Carbon::parse($kegiatan->tanggal)->format('d M Y') }}
                                            </td>
                                            <td class="px-4 py-2">
                                                @php
                                                    $status = optional($kegiatan->absensi->first())->status;
                                                @endphp

                                                @if ($status == 'hadir')
                                                    <span
                                                        class="px-2 py-1 rounded-full bg-green-100 text-green-800 text-xs font-semibold">Hadir</span>
                                                @elseif ($status == 'sakit')
                                                    <span
                                                        class="px-2 py-1 rounded-full bg-red-100 text-red-800 text-xs font-semibold">Sakit</span>
                                                @elseif ($status == 'izin')
                                                    <span
                                                        class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-semibold">Izin</span>
                                                @else
                                                    <span
                                                        class="px-2 py-1 rounded-full bg-gray-100 text-gray-800 text-xs font-semibold">Absen</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- GRID 3: Settings Form --}}
                    <div class="bg-white p-6 rounded-2xl shadow-md">
                        <h3 class="text-xl font-bold mb-4">Pengaturan Akun</h3>
                        @if (session('status'))
                            <div class="mb-4 text-sm text-green-700 bg-green-100 border border-green-400 p-3 rounded-md">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="mb-4 text-sm text-red-700 bg-red-100 border border-red-400 p-3 rounded-md">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" action="{{ route('peserta.settings.update') }}" class="space-y-4">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label for="nama" class="block text-gray-700 font-bold mb-1">Nama</label>
                                <input type="text" name="nama" id="nama" value="{{ old('nama', $peserta->nama) }}" required
                                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                            </div>
                            <div>
                                <label for="no_hp" class="block text-gray-700 font-bold mb-1">Nomor HP</label>
                                <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $peserta->no_hp) }}"
                                    required
                                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                            </div>
                            <div>
                                <label for="password" class="block text-gray-700 font-bold mb-1">Password Baru
                                    (opsional)</label>
                                <input type="password" name="password" id="password"
                                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-gray-700 font-bold mb-1">Konfirmasi
                                    Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                            </div>
                            <div class="flex justify-end">
                                <button type="submit"
                                    class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg shadow">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection