@extends('layouts.app')

@section('title', 'Manajemen Kelas')

@section('header')
    🏫 Manajemen Kelas
@endsection

@section('content')
<div x-data="{ openModal: false, modalType: '', kelasId: null, kelasNama: '', kelasProdiId: '' }">
    <div class="animate-fadeIn">
        <div class="bg-white rounded-lg shadow-md card-simple p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-800">Daftar Kelas</h2>
                <button @click="openModal = true; modalType = 'add'; kelasNama = ''; kelasProdiId = '';" class="btn-hover px-4 py-2 red-gradient text-white rounded-md font-semibold shadow-lg">Tambah Kelas</button>
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
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nama Kelas</th>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Prodi</th>
                            <th class="text-center py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @forelse($kelasList as $index => $kelas)
                            <tr class="border-b">
                                <td class="py-3 px-4">{{ $kelasList->firstItem() + $index }}</td>
                                <td class="py-3 px-4">{{ $kelas->nama }}</td>
                                <td class="py-3 px-4">{{ $kelas->prodi->nama }}</td>
                                <td class="text-center py-3 px-4">
                                    <button @click="openModal = true; modalType = 'edit'; kelasId = {{ $kelas->id }}; kelasNama = '{{ $kelas->nama }}'; kelasProdiId = '{{ $kelas->prodi_id }}';" class="text-blue-500 hover:text-blue-700 font-semibold">Edit</button>
                                    <form action="{{ route('kelas.destroy', $kelas->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-semibold ml-4">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">Tidak ada data kelas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $kelasList->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div x-show="openModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click.away="openModal = false">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md" @click.stop>
            <h3 class="text-lg font-semibold mb-4" x-text="modalType === 'add' ? 'Tambah Kelas Baru' : 'Edit Kelas'"></h3>
            <form :action="modalType === 'add' ? '{{ route('kelas.store') }}' : '{{ url('kelas') }}/' + kelasId" method="POST">
                @csrf
                <template x-if="modalType === 'edit'">
                    @method('PUT')
                </template>
                <div class="space-y-4">
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                        <input type="text" name="nama" id="nama" x-model="kelasNama" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                    </div>
                    <div>
                        <label for="prodi_id" class="block text-sm font-medium text-gray-700">Prodi</label>
                        <select name="prodi_id" id="prodi_id" x-model="kelasProdiId" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                            <option value="" disabled>Pilih Prodi</option>
                            @foreach($prodis as $prodi)
                                <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-4">
                    <button type="button" @click="openModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Batal</button>
                    <button type="submit" class="px-4 py-2 red-gradient text-white rounded-md">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
@endsection
