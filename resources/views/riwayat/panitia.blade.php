@extends('layouts.app')

@section('header')
     📋 Absensi Peserta
@endsection

@section('content')
    <div class="py-12" x-data="{ isModalOpen: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                 <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">Data Riwayat</h3>
                        <button @click="isModalOpen = true" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Tambah Manual
                        </button>
                    </div>

                    <form method="GET" action="{{ route('riwayat.panitia') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="kegiatan_id" class="block text-sm font-medium text-gray-700">Filter Kegiatan</label>
                                <select name="kegiatan_id" id="kegiatan_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Semua Kegiatan</option>
                                    @foreach ($kegiatan as $item)
                                        <option value="{{ $item->id }}" {{ request('kegiatan_id') == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama_kegiatan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700">Cari Nama Panitia</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Masukkan nama...">
                            </div>
                             <div class="flex items-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Cari
                                </button>
                                <a href="{{ route('riwayat.panitia') }}" class="ml-3 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Peserta</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kegiatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Absen</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($riwayat as $item)
                                    <tr>
                                        <td class="px-6 py-4">{{ $loop->iteration + $riwayat->firstItem() - 1 }}</td>
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $item->panitia->nama ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-gray-500">{{ $item->kegiatan->nama_kegiatan ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 text-gray-500">{{ $item->created_at->format('d F Y, H:i:s') }}</td>
                                        {{-- --- PERBAIKAN TAMPILAN STATUS --- --}}
                                        <td class="px-6 py-4">
                                            @if($item->status == 'hadir')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Hadir</span>
                                            @elseif($item->status == 'tidak_hadir')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Sakit</span>
                                            @elseif($item->status == 'izin')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Izin</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($item->status) }}</span>
                                            @endif
                                        </td>
                                        {{-- --- AKHIR PERBAIKAN --- --}}
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $riwayat->links() }}</div>
                </div>
            </div>
        </div>

        {{-- MODAL --}}
        <div x-show="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
            <div @click.away="isModalOpen = false" class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Absensi Manual</h3>
                <form action="{{ route('riwayat.manual.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tipe" value="panitia">
                    <div class="space-y-4">
                        <div>
                            <label for="panitia_id" class="block text-sm font-medium text-gray-700">Pilih Panitia</label>
                            <select name="panitia_id" id="panitia_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">-- Pilih Panitia --</option>
                                @foreach($panitia as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="id_kegiatan" class="block text-sm font-medium text-gray-700">Pilih Kegiatan</label>
                            <select name="id_kegiatan" id="id_kegiatan" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">-- Pilih Kegiatan --</option>
                                @foreach($kegiatan as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama_kegiatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- --- PERBAIKAN OPSI STATUS --- --}}
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="izin">Izin</option>
                                <option value="tidak_hadir">Sakit</option>
                            </select>
                        </div>
                        {{-- --- AKHIR PERBAIKAN --- --}}
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="isModalOpen = false" class="px-4 py-2 bg-white border rounded-md font-semibold text-xs text-gray-700 uppercase hover:bg-gray-50">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-gray-800 border rounded-md font-semibold text-xs text-white uppercase hover:bg-gray-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection