@extends('layouts.app')

@section('title', 'Tambah Kegiatan Baru')

@section('header')
    ➕ Tambah Kegiatan Baru
@endsection

@section('content')
    <div class="animate-fadeIn">
        <div class="bg-white rounded-lg shadow-md card-simple p-6">
            <form action="{{ route('kegiatan.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">

                    <!-- Nama Kegiatan -->
                    <div class="md:col-span-2">
                        <label for="nama_kegiatan" class="block text-sm font-medium text-gray-700 mb-1">Nama
                            Kegiatan</label>
                        <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="input-field"
                            value="{{ old('nama_kegiatan') }}" required>
                        @error('nama_kegiatan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="input-field" value="{{ old('tanggal') }}"
                            required>
                        @error('tanggal')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Waktu -->
                    <div>
                        <label for="waktu" class="block text-sm font-medium text-gray-700 mb-1">Waktu</label>
                        <input type="time" name="waktu" id="waktu" class="input-field" value="{{ old('waktu') }}" required>
                        @error('waktu')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lokasi -->
                    <div class="md:col-span-2">
                        <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                        <input type="text" name="lokasi" id="lokasi" class="input-field" value="{{ old('lokasi') }}"
                            required>
                        @error('lokasi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Target Opsional -->
                    <div class="md:col-span-2 border-t pt-4 mt-2">
                        <p class="text-sm text-gray-600 mb-2"><b>Target Peserta (Opsional):</b> Kosongkan jika kegiatan ini terbuka untuk umum.</p>
                    </div>

                    <!-- Target Prodi -->
                    <div>
                        <label for="target_prodi" class="block text-sm font-medium text-gray-700 mb-1">Target Prodi</label>
                        <input type="text" name="target_prodi" id="target_prodi" class="input-field"
                            value="{{ old('target_prodi') }}">
                    </div>

                    <!-- Target Kelas -->
                    <div>
                        <label for="target_kelas" class="block text-sm font-medium text-gray-700 mb-1">Target Kelas</label>
                        <input type="text" name="target_kelas" id="target_kelas" class="input-field"
                            value="{{ old('target_kelas') }}">
                    </div>

                    <!-- Target Tingkat -->
                    <div>
                        <label for="target_tingkat" class="block text-sm font-medium text-gray-700 mb-1">Target Tingkat</label>
                        <input type="text" name="target_tingkat" id="target_tingkat" class="input-field"
                            value="{{ old('target_tingkat') }}">
                    </div>

                    <!-- Target Jabatan -->
                    <div>
                        <label for="target_jabatan" class="block text-sm font-medium text-gray-700 mb-1">Target Jabatan</label>
                        <input type="text" name="target_jabatan" id="target_jabatan" class="input-field"
                            value="{{ old('target_jabatan') }}">
                    </div>

                    <!-- Deskripsi -->
                    <div class="md:col-span-2">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi
                            (Opsional)</label>
                        <textarea name="deskripsi" id="deskripsi" rows="4"
                            class="input-field">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end items-center mt-8 gap-4">
                    <a href="{{ route('kegiatan.index') }}"
                        class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-md font-semibold hover:bg-gray-300 transition-colors duration-200">Batal</a>
                    <button type="submit"
                        class="px-6 py-2.5 red-gradient text-white rounded-md font-semibold shadow-lg btn-hover">Simpan
                        Kegiatan</button>
                </div>
            </form>
        </div>
    </div>
@endsection