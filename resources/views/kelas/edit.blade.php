@extends('layouts.app')

@section('header')<h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Kelas</h2>@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('kelas.update', $kelas->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="nama" class="block font-medium text-sm text-gray-700">Nama Kelas</label>
                            <input type="text" name="nama" id="nama" class="mt-1 block w-full rounded-md"
                                value="{{ old('nama', $kelas->nama) }}" required>
                            @error('nama')<p class="text-sm text-red-600 mt-2">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="prodi_id" class="block font-medium text-sm text-gray-700">Prodi</label>
                            <select name="prodi_id" id="prodi_id" class="mt-1 block w-full rounded-md" required>
                                @foreach($prodis as $prodi)
                                    <option value="{{ $prodi->id }}" {{ old('prodi_id', $kelas->prodi_id) == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama }}</option>
                                @endforeach
                            </select>
                            @error('prodi_id')<p class="text-sm text-red-600 mt-2">{{ $message }}</p>@enderror
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('master.akademik') }}" class="text-sm text-gray-600 mr-4">Batal</a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border rounded-md font-semibold text-xs text-white uppercase">Simpan
                                Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection