@extends('layouts.app')

@section('header')<h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Jabatan</h2>@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('jabatan.update', $jabatan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="nama" class="block font-medium text-sm text-gray-700">Nama Jabatan</label>
                            <input type="text" name="nama" id="nama"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('nama', $jabatan->nama) }}" required>
                            @error('nama')<p class="text-sm text-red-600 mt-2">{{ $message }}</p>@enderror
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('master.organisasi') }}"
                                class="text-sm text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border rounded-md font-semibold text-xs text-white uppercase hover:bg-gray-700">Simpan
                                Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection