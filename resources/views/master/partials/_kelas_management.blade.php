{{-- resources/views/admin/master/partials/_kelas_management.blade.php --}}

<h3 class="text-lg font-semibold border-b pb-2 mb-4">Manajemen Kelas</h3>

<form action="{{ route('kelas.store') }}" method="POST" class="mb-6 space-y-4">
    @csrf
    <div>
        <label for="kelas_nama" class="sr-only">Nama Kelas</label>
        <input type="text" name="nama" id="kelas_nama" class="block w-full rounded-md border-gray-300 shadow-sm"
            placeholder="Masukkan nama kelas baru..." required>
    </div>
    <div>
        <label for="prodi_id" class="sr-only">Pilih Prodi</label>
        <select name="prodi_id" id="prodi_id" class="block w-full rounded-md border-gray-300 shadow-sm" required>
            <option value="">-- Pilih Prodi Terkait --</option>
            @foreach($allProdis as $prodi)
                <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit"
        class="inline-flex items-center px-4 py-2 bg-gray-800 border rounded-md font-semibold text-xs text-white uppercase hover:bg-gray-700">
        Simpan
    </button>
</form>

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Kelas</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prodi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($kelasList as $kelas)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $kelas->nama }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $kelas->prodi->nama ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <form action="{{ route('kelas.destroy', $kelas->id) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus kelas ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada data kelas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $kelasList->links() }}
</div>