@extends('layouts.app')

@section('header')
    📋 Absensi Peserta
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                {{-- Notifikasi sukses --}}
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                {{-- Notifikasi error --}}
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
                        <h3 class="text-lg font-medium">Data Riwayat</h3>
                        <div class="flex space-x-2">
                             {{-- Tombol untuk mengekspor data ke Excel --}}
                             <a href="{{ route('riwayat.peserta.export', request()->query()) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500">
                                 Export Excel
                             </a>
                             {{-- Tombol untuk membuka modal Tambah Manual --}}
                             <button id="openModalBtn" class="inline-flex items-center px-4 py-2 bg-blue-600 border rounded-md font-semibold text-xs text-white uppercase hover:bg-blue-500">
                                 Tambah Manual
                             </button>
                        </div>
                    </div>

                    {{-- Formulir Filter dan Pencarian --}}
                    <form method="GET" action="{{ route('riwayat.peserta') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
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
                                <label for="search" class="block text-sm font-medium text-gray-700">Cari Nama</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Masukkan nama">
                            </div>
                            <div class="flex space-x-2">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Cari
                                </button>
                                <a href="{{ route('riwayat.peserta') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    {{-- Tabel Data Riwayat --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Peserta</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NPM</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kegiatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Absen</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($pesertaList as $item)
                                    <tr>
                                        {{-- Menggunakan $pesertaList untuk paginasi --}}
                                        <td class="px-6 py-4">{{ $loop->iteration + $pesertaList->firstItem() - 1 }}</td>
                                        {{-- Mengakses langsung nama dan npm karena sudah di join --}}
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $item->nama ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->npm }}</td>
                                        {{-- Mendapatkan nama kegiatan dari request --}}
                                        <td class="px-6 py-4 text-gray-500">
                                            @if(request('kegiatan_id'))
                                                {{ $kegiatan->firstWhere('id', request('kegiatan_id'))->nama_kegiatan ?? 'N/A' }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        {{-- Waktu absen tidak tersedia di query saat ini --}}
                                        <td class="px-6 py-4 text-gray-500">N/A</td>
                                        <td class="px-6 py-4 text-gray-500">{{ $item->keterangan ?? 'N/A' }}</td>
                                        <td class="px-6 py-4">
                                            @php
                                                // Mendapatkan status dengan lebih aman
                                                $status = $item->status ?? 'belum_hadir';
                                            @endphp
                                            @if($status == 'hadir')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Hadir</span>
                                            @elseif($status == 'tidak_hadir')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Sakit</span>
                                            @elseif($status == 'izin')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Izin</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Belum Hadir</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if($item->absensi_id)
                                                {{-- Tombol untuk membuka modal edit dengan data yang relevan --}}
                                                <button 
                                                    class="text-indigo-600 hover:text-indigo-900"
                                                    onclick="openEditModal('{{ $item->absensi_id }}', '{{ $item->status }}', '{{ $item->keterangan }}')">
                                                    Edit
                                                </button>
                                                <span class="text-gray-300 mx-1">|</span>
                                                {{-- Tombol untuk membuka modal konfirmasi hapus --}}
                                                <button 
                                                    class="text-red-600 hover:text-red-900"
                                                    onclick="openDeleteModal('{{ $item->absensi_id }}')">
                                                    Hapus
                                                </button>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="px-6 py-4 text-center text-gray-500">Tidak ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination --}}
                    <div class="mt-4">{{ $pesertaList->links() }}</div>
                </div>
            </div>
        </div>

       {{-- MODAL DIBERI ID BARU DAN KELAS 'hidden' --}}
        <div id="manualAttendanceModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div id="modalContent" class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Absensi Manual</h3>
                <form action="{{ route('riwayat.manual.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tipe" value="peserta">
                    <div class="space-y-4">
                        {{-- Form input dengan error handling (tidak berubah) --}}
                         <div>
                            <label for="peserta_id" class="block text-sm font-medium text-gray-700">Pilih Peserta</label>
                            <select name="peserta_id" id="peserta_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('peserta_id') border-red-500 @enderror">
                                <option value="">-- Pilih Peserta --</option>
                                @foreach($pesertaList as $p)
                                    <option value="{{ $p->id }}" {{ old('peserta_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }} ({{ $p->npm }})</option>
                                @endforeach
                            </select>
                            @error('peserta_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="kegiatan_id" class="block text-sm font-medium text-gray-700">Pilih Kegiatan</label>
                            {{-- Ubah name, id, dan error key menjadi 'kegiatan_id' --}}
                            <select name="kegiatan_id" id="kegiatan_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('kegiatan_id') border-red-500 @enderror">
                                <option value="">-- Pilih Kegiatan --</option>
                                @foreach($kegiatan as $k)
                                    <option value="{{ $k->id }}" {{ old('kegiatan_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kegiatan }}</option>
                                @endforeach
                            </select>
                            @error('kegiatan_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="hadir" {{ old('status') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                <option value="izin" {{ old('status') == 'izin' ? 'selected' : '' }}>Izin</option>
                                <option value="tidak_hadir" {{ old('status') == 'tidak_hadir' ? 'selected' : '' }}>Sakit</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        {{-- TOMBOL BATAL DIBERI ID BARU --}}
                        <button type="button" id="closeModalBtn" class="px-4 py-2 bg-white border rounded-md font-semibold text-xs text-gray-700 uppercase hover:bg-gray-50">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-gray-800 border rounded-md font-semibold text-xs text-white uppercase hover:bg-gray-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

        {{-- MODAL UNTUK EDIT RIWAYAT --}}
        <div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Riwayat Absensi</h3>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label for="edit_status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="edit_status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="hadir">Hadir</option>
                                <option value="izin">Izin</option>
                                <option value="tidak_hadir">Sakit</option>
                            </select>
                        </div>
                        <div>
                            <label for="edit_keterangan" class="block text-sm font-medium text-gray-700">Keterangan (Opsional)</label>
                            <textarea name="keterangan" id="edit_keterangan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-white border rounded-md font-semibold text-xs text-gray-700 uppercase hover:bg-gray-50">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-gray-800 border rounded-md font-semibold text-xs text-white uppercase hover:bg-gray-700">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL UNTUK KONFIRMASI HAPUS --}}
        <div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-medium text-gray-900">Konfirmasi Hapus</h3>
                <p class="mt-2 text-sm text-gray-600">Apakah Anda yakin ingin menghapus riwayat absensi ini? Tindakan ini tidak dapat dibatalkan.</p>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-white border rounded-md font-semibold text-xs text-gray-700 uppercase hover:bg-gray-50">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-red-600 border rounded-md font-semibold text-xs text-white uppercase hover:bg-red-500">Ya, Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- LOGIKA MODAL TAMBAH MANUAL ---
        const manualAttendanceModal = document.getElementById('manualAttendanceModal');
        const openModalBtn = document.getElementById('openModalBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const modalContent = document.getElementById('modalContent');

        function openManualModal() {
            if (manualAttendanceModal) {
                manualAttendanceModal.classList.remove('hidden');
            }
        }

        function closeManualModal() {
            if (manualAttendanceModal) {
                manualAttendanceModal.classList.add('hidden');
            }
        }
        
        if (openModalBtn) {
            openModalBtn.addEventListener('click', openManualModal);
        }

        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', closeManualModal);
        }

        if (manualAttendanceModal) {
            manualAttendanceModal.addEventListener('click', function (event) {
                if (event.target === manualAttendanceModal) {
                    closeManualModal();
                }
            });
        }
        
        @if ($errors->any())
            openManualModal();
        @endif

        // --- LOGIKA MODAL EDIT RIWAYAT ---
        const editModal = document.getElementById('editModal');
        const editForm = document.getElementById('editForm');
        const editStatus = document.getElementById('edit_status');
        const editKeterangan = document.getElementById('edit_keterangan');

        window.openEditModal = function(id, status, keterangan) {
            editForm.action = `/riwayat-absensi/${id}`;
            editStatus.value = status;
            editKeterangan.value = keterangan;
            editModal.classList.remove('hidden');
        }

        window.closeEditModal = function() {
            editModal.classList.add('hidden');
        }

        if (editModal) {
            editModal.addEventListener('click', function (event) {
                if (event.target === editModal) {
                    closeEditModal();
                }
            });
        }

        // --- LOGIKA MODAL HAPUS RIWAYAT ---
        const deleteModal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');

        window.openDeleteModal = function(id) {
            deleteForm.action = `/riwayat-absensi/${id}`;
            deleteModal.classList.remove('hidden');
        }

        window.closeDeleteModal = function() {
            deleteModal.classList.add('hidden');
        }

        if (deleteModal) {
            deleteModal.addEventListener('click', function (event) {
                if (event.target === deleteModal) {
                    closeDeleteModal();
                }
            });
        }
    });
</script>
@endpush