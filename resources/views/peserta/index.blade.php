@extends('layouts.app')

@section('title', 'Manajemen peserta')

@section('header')
    👥 Manajemen Peserta
@endsection

@section('content')
    <div class="animate-fadeIn">
        <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
            <form action="{{ route('peserta.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label for="prodi_id" class="block text-sm font-medium text-gray-700">Filter Prodi:</label>
                    <select name="prodi_id" id="prodi_id"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">Semua Prodi</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi->id }}" {{ request('prodi_id') == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-[200px]">
                    <label for="kelas_id" class="block text-sm font-medium text-gray-700">Filter Kelas:</label>
                    <select name="kelas_id" id="kelas_id"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">Semua Kelas</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-5">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Filter
                    </button>
                    <a href="{{ route('peserta.index') }}"
                        class="ml-2 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow-md card-simple p-6">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                <a href="{{ route('dashboard') }}"
                    class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md font-semibold shadow-md transition">
                    ← Dashboard
                </a>
                <h3 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">Daftar Peserta</h3>
                <a href="{{ route('peserta.create') }}"
                    class="btn-hover w-full sm:w-auto px-5 py-2 red-gradient text-white rounded-md font-semibold shadow-lg">
                    Tambah peserta
                </a>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border-red-400 text-red-700 px-4 py-3 rounded">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                            </th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email
                            </th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NPM
                            </th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.
                                HP</th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prodi
                            </th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">kelas
                            </th>
                            <th class="py-3 px-6 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($peserta as $p)
                            <tr>
                                <td class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap">{{ $p->nama }}</td>
                                <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">{{ $p->email }}</td>
                                <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">{{ $p->npm }}</td>
                                <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">{{ $p->no_hp }}</td>
                                <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                                    {{ $p->kelas->prodi->nama ?? '-' }}
                                </td>
                                <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">{{ $p->kelas->nama ?? '-' }}</td>
                                <td class="py-4 px-6 text-sm text-center whitespace-nowrap">
                                    <div class="flex justify-center items-center gap-2">
                                        <a href="{{ route('peserta.qr', $p->id) }}" class="text-green-600 hover:text-green-900"
                                            title="Tampilkan QR Code">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 16h4.01M20 12h.01M12 8h4.01M8 16h.01M4 16h.01M4 20h.01M12 20h.01M16 20h.01M20 20h.01M12 16h.01M8 12h.01M4 12h.01M4 8h.01M8 8h.01" />
                                            </svg>
                                        </a>
                                        <a href="{{ route('peserta.edit', [$p->id]) }}"
                                            class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L14.732 5.232z" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('peserta.destroy', $p->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus peserta ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12">
                                    <div class="text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                        </svg>
                                        <p class="text-lg font-semibold">Belum ada data peserta.</p>
                                        <p class="text-sm">Silakan tambahkan peserta baru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $peserta->links() }}
            </div>
        </div>
    </div>
@endsection