@extends('layouts.app')

@section('title', 'Manajemen Divisi')

@section('header')
    🏢 Manajemen Divisi
@endsection

@section('content')
<div 
    x-data="{
        openModal: false, 
        modalType: '', 
        divisiId: null, 
        divisiNama: '', 
        jabatanId: null 
    }"
>
    <div class="animate-fadeIn">
        <div class="bg-white rounded-lg shadow-md card-simple p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Daftar Divisi</h2>
                <button @click="openModal = true; modalType = 'add'; divisiNama = ''; jabatanId = null;"
                    class="btn-hover px-4 py-2 red-gradient text-white rounded-md font-semibold shadow-lg">Tambah
                    Divisi</button>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">#</th>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nama Divisi</th>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Jabatan</th>
                            <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @forelse($divisis as $index => $divisi)
                            <tr class="border-b">
                                <td class="py-3 px-4">{{ $divisis->firstItem() + $index }}</td>
                                <td class="py-3 px-4">{{ $divisi->nama }}</td>
                                <td>{{ $divisi->jabatan->nama ?? '-' }}</td>
                                <td class="text-center py-3 px-4">
                                    <button @click="openModal = true; modalType = 'edit'; divisiId = {{ $divisi->id }}; divisiNama = '{{ $divisi->nama }}'; jabatanId = {{ $divisi->jabatan_id }};"
                                        class="text-blue-500 hover:text-blue-700 font-semibold">Edit</button>
                                    <form action="{{ route('divisi.destroy', $divisi->id) }}" method="POST"
                                        class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus divisi ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-500 hover:text-red-700 font-semibold ml-4">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">Tidak ada data divisi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $divisis->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div x-show="openModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        @click.away="openModal = false">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md" @click.stop>
            <h3 class="text-lg font-semibold mb-4" x-text="modalType === 'add' ? 'Tambah Divisi Baru' : 'Edit Divisi'"></h3>
            <form :action="modalType === 'add' ? '{{ route('divisi.store') }}' : '{{ url('divisi') }}/' + divisiId"
                method="POST">
                @csrf
                <template x-if="modalType === 'edit'">
                    @method('PUT')
                </template>
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Divisi</label>
                    <input type="text" name="nama" id="nama" x-model="divisiNama"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                        required>
                    @error('nama')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-4">
                    <label for="jabatan_id" class="block text-sm font-medium text-gray-700">Jabatan</label>
                    <select name="jabatan_id" id="jabatan_id" x-model="jabatanId"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                        required>
                        <option value="">Pilih Jabatan</option>
                        @foreach($jabatans as $jabatan)
                            <option value="{{ $jabatan->id }}">{{ $jabatan->nama }}</option>
                        @endforeach
                    </select>
                    @error('jabatan_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6 flex justify-end gap-4">
                    <button type="button" @click="openModal = false"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Batal</button>
                    <button type="submit" class="px-4 py-2 red-gradient text-white rounded-md">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
@endsection
