@extends('layouts.user_app')

@section('header')
    📋 Dashboard Peserta
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Menggunakan layout grid yang Anda berikan --}}
            <div class="grid grid-cols-5 grid-rows-5 gap-4">

                {{-- GRID 1: QR CODE & Info --}}
                <div
                    class="col-span-2 row-span-5 bg-white p-6 rounded-lg shadow-md flex flex-col items-center justify-center">
                    <h3 class="text-xl font-bold mb-4">QR Code Absensi</h3>
                    <div class="p-4 bg-gray-100 rounded-md">
                        <div class="qr-code">
                            {!! QrCode::size(300)->generate($peserta->barcode) !!}
                        </div>
                    </div>
                    <p class="mt-4 text-sm text-gray-600">Tunjukkan kode ini untuk absensi</p>
                    <p class="text-lg font-bold text-gray-800 mt-2">{{ $peserta->nama }}</p>
                    <p class="text-sm text-gray-500">{{ $peserta->npm }}</p>
                </div>

                {{-- GRID 2: STATISTIK KEHADIRAN --}}
                <div class="col-span-3 row-span-3 col-start-3 bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold mb-4">Statistik Kehadiran</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-center">
                        <div class="bg-blue-100 p-4 rounded-md">
                            <p class="text-4xl font-bold text-blue-800">{{ $hadirPercentage }}%</p>
                            <p class="text-sm text-blue-600">Overall Kehadiran</p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-md">
                            <p class="text-3xl font-bold text-green-800">{{ $hadirCount }}</p>
                            <p class="text-sm text-green-600">Hadir</p>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded-md">
                            <p class="text-3xl font-bold text-yellow-800">{{ $izinCount }}</p>
                            <p class="text-sm text-yellow-600">Izin</p>
                        </div>
                        <div class="bg-red-100 p-4 rounded-md">
                            <p class="text-3xl font-bold text-red-800">{{ $sakitCount }}</p>
                            <p class="text-sm text-red-600">Sakit</p>
                        </div>
                    </div>

                    <h3 class="text-lg font-bold mt-8 mb-4">Riwayat Kehadiran Terakhir</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kegiatan</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($riwayatAbsensi as $absen)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $absen->kegiatan->nama_kegiatan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($absen->kegiatan->tanggal)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($absen->waktu_hadir)
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Hadir</span>
                                            @else
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ $absen->keterangan ?? 'Belum Absen' }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada riwayat absensi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- GRID 3: SETTINGS FORM --}}
                <div class="col-span-3 row-span-2 col-start-3 row-start-4 bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold mb-4">Pengaturan Akun</h3>
                    @if (session('status'))
                        <div
                            class="mb-4 font-medium text-sm text-green-600 bg-green-100 border border-green-400 p-3 rounded-md">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="mb-4 text-sm text-red-600 bg-red-100 border border-red-400 p-3 rounded-md">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('peserta.settings.update') }}">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                            <label for="nama" class="block text-gray-700 font-bold mb-2">Nama</label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $peserta->nama) }}" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="no_hp" class="block text-gray-700 font-bold mb-2">Nomor HP</label>
                            <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $peserta->no_hp) }}" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="password" class="block text-gray-700 font-bold mb-2">Password Baru
                                (opsional)</label>
                            <input type="password" name="password" id="password"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-gray-700 font-bold mb-2">Konfirmasi
                                Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="flex items-center justify-end">
                            <button type="submit"
                                class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection