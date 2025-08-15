{{-- resources/views/admin/master/partials/_jabatan_management.blade.php --}}

<h3 class="text-lg font-semibold border-b pb-2 mb-4">Manajemen Jabatan</h3>

<form action="{{ route('jabatan.store') }}" method="POST" class="mb-6">
    @csrf
    <label for="jabatan_nama" class="sr-only">Nama Jabatan</label>
    <div class="flex items-center">
        <input type="text" name="nama" id="jabatan_nama" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
            placeholder="Masukkan nama jabatan baru..." required>
        <button type="submit"
            class="ml-2 inline-flex items-center px-4 py-2 bg-gray-800 border rounded-md font-semibold text-xs text-white uppercase hover:bg-gray-700">
            Simpan
        </button>
    </div>
</form>

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Jabatan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($jabatans as $jabatan)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $jabatan->nama }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <form action="{{ route('jabatan.destroy', $jabatan->id) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus jabatan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="px-6 py-4 text-center text-gray-500">Belum ada data jabatan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $jabatans->links() }}
</div>