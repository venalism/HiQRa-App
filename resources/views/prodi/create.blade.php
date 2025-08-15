@extends('layouts.app')
@section('header') 
<h2 class="font-semibold text-xl">Tambah Prodi Baru</h2> 
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('prodi.store') }}" method="POST">
                        @csrf
                        <div>
                            <label for="nama" class="block font-medium text-sm text-gray-700">Nama Prodi</label>
                            <input type="text" name="nama" id="nama" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('master.akademik') }}" class="mr-4">Batal</a>
                            <button type="submit">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection