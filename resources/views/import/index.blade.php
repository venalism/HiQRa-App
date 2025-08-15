@extends('layouts.app')

@section('header')
    Import Data
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Notifikasi --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Terjadi Kesalahan Validasi!</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Kolom Kiri: Form Upload --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4 border-b pb-2">Upload File</h3>

                        <form action="{{ route('import.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label for="import_type" class="block text-sm font-medium text-gray-700">Pilih Jenis
                                        Data</label>
                                    <select name="import_type" id="import_type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">-- Pilih Jenis Data --</option>
                                        <option value="peserta" {{ old('import_type') == 'peserta' ? 'selected' : '' }}>
                                            Peserta</option>
                                        <option value="panitia" {{ old('import_type') == 'panitia' ? 'selected' : '' }}>
                                            Panitia</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="file" class="block text-sm font-medium text-gray-700">Pilih File Excel
                                        (.xlsx, .xls)</label>
                                    <input type="file" name="file" id="file" required
                                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                </div>
                            </div>
                            <div class="mt-6">
                                <button type="submit"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-800 border rounded-md font-semibold text-xs text-white uppercase hover:bg-gray-700">
                                    Upload & Import
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Kolom Kanan: Panduan --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium mb-4 border-b pb-2">Panduan & Template</h3>
                        <div class="text-sm text-gray-600 space-y-4">
                            <p>
                                Silakan unduh template yang sesuai untuk memastikan format data Anda benar. Isi data dimulai
                                dari baris kedua, tepat di bawah header.
                            </p>
                            <div>
                                <h4 class="font-semibold">Template Peserta</h4>
                                <p class="text-xs text-gray-500 mb-1">Kolom yang dibutuhkan: nama, email, no_hp, prodi,
                                    kelas.</p>
                                <a href="{{ route('import.template', 'peserta') }}"
                                    class="text-blue-600 hover:underline">Unduh Template Peserta.xlsx</a>
                            </div>
                            <div>
                                <h4 class="font-semibold">Template Panitia</h4>
                                <p class="text-xs text-gray-500 mb-1">Kolom yang dibutuhkan: nama, email, no_hp, jabatan,
                                    divisi.</p>
                                <a href="{{ route('import.template', 'panitia') }}"
                                    class="text-blue-600 hover:underline">Unduh Template Panitia.xlsx</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection