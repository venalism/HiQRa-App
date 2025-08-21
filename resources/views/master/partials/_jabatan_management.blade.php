<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="flex justify-between items-center border-b pb-2 mb-4">
            <h3 class="text-lg font-semibold">Manajemen Jabatan</h3>
            <a href="{{ route('jabatan.create') }}" class="inline-flex items-center px-3 py-2 bg-gray-800 border rounded-md font-semibold text-xs text-white uppercase hover:bg-gray-700">
                Tambah
            </a>
        </div>

        <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($jabatans as $jabatan)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $jabatan->nama }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('jabatan.edit', $jabatan->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form action="{{ route('jabatan.destroy', $jabatan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus prodi ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="px-6 py-4 text-center text-gray-500">Data kosong.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
        {{-- Paginasi --}}
        <div class="mt-4">{{ $jabatans->links() }}</div>
    </div>
</div>