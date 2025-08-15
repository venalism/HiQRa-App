@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Edit Prodi
    </h2>
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('prodi.update', $prodi->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="nama" class="block font-medium text-sm text-gray-700">Nama Prodi</label>
                            <input type="text" name="nama" id="nama" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ $prodi->nama }}" required>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('master.akademik') }}" class="mr-4">Batal</a>
                            <button type="submit">Simpan Perubahan</button>
                        </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection