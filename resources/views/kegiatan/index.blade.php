@extends('layouts.app')

@section('title', 'Manajemen Kegiatan')

@section('header')
    🗓️ Manajemen Kegiatan
@endsection

@section('content')
    <div class="animate-fadeIn">
        <div class="bg-white rounded-lg shadow-md card-simple p-6">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">Daftar Kegiatan</h3>
                <a href="{{ route('kegiatan.create') }}"
                    class="btn-hover w-full sm:w-auto px-5 py-2 red-gradient text-white rounded-md font-semibold shadow-lg">
                    Tambah Kegiatan
                </a>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                                Kegiatan</th>
                            <th scope="col"
                                class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th scope="col"
                                class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu
                            </th>
                            <th scope="col"
                                class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Lokasi</th>
                            <th scope="col"
                                class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($kegiatans as $kegiatan)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="py-4 px-6 text-sm font-medium text-gray-900">{{ $kegiatan->nama_kegiatan }}</td>
                                <td class="py-4 px-6 text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('d F Y') }}</td>
                                <td class="py-4 px-6 text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($kegiatan->waktu)->format('H:i') }} WIB</td>
                                <td class="py-4 px-6 text-sm text-gray-600">{{ $kegiatan->lokasi }}</td>
                                <td class="py-4 px-6 text-sm font-medium text-center">
                                    <div class="flex justify-center items-center space-x-2">
                                        <a href="{{ route('kegiatan.edit', $kegiatan->id) }}"
                                            class="text-yellow-500 hover:text-yellow-700" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.536L16.732 3.732z">
                                                </path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('kegiatan.destroy', $kegiatan->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700" title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12">
                                    <div class="text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <p class="text-lg font-semibold">Belum ada data kegiatan.</p>
                                        <p class="text-sm">Silakan tambahkan kegiatan baru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $kegiatans->links() }}
            </div>
        </div>
    </div>
@endsection