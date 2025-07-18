@extends('layouts.app')

@section('title', 'Edit Peserta')

@section('header')
    ✏️ Edit Peserta
@endsection

@section('content')
    <div class="animate-fadeIn">
        <div class="bg-white rounded-lg shadow-md card-simple p-6">
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                    <strong class="font-bold">Oops! Terjadi kesalahan.</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('peserta.update', $peserta->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">

                    <!-- Nama -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama" id="nama" class="input-field"
                            value="{{ old('nama', $peserta->nama) }}" required>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" class="input-field"
                            value="{{ old('email', $peserta->email) }}" required>
                    </div>

                    <!-- No HP -->
                    <div>
                        <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">No. HP (Opsional)</label>
                        <input type="tel" name="no_hp" id="no_hp" class="input-field"
                            value="{{ old('no_hp', $peserta->no_hp) }}">
                    </div>

                    <!-- Jabatan -->
                    <div>
                        <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-1">Jabatan (Opsional)</label>
                        <input type="text" name="jabatan" id="jabatan" class="input-field"
                            value="{{ old('jabatan', $peserta->jabatan) }}">
                    </div>

                    <!-- Prodi -->
                    <div>
                        <label for="prodi" class="block text-sm font-medium text-gray-700 mb-1">Prodi (Opsional)</label>
                        <select name="prodi" id="prodi" class="input-field">
                            <option value="">Pilih Prodi</option>
                            @foreach($prodiOptions as $option)
                                <option value="{{ $option }}" {{ old('prodi', $peserta->prodi) == $option ? 'selected' : '' }}>{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kelas -->
                    <div>
                        <label for="kelas" class="block text-sm font-medium text-gray-700 mb-1">Kelas (Opsional)</label>
                        <select name="kelas" id="kelas" class="input-field">
                            <option value="">Pilih Kelas</option>
                            @foreach($kelasOptions as $option)
                                <option value="{{ $option }}" {{ old('kelas', $peserta->kelas) == $option ? 'selected' : '' }}>{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tingkat -->
                    <div class="md:col-span-2">
                        <label for="tingkat" class="block text-sm font-medium text-gray-700 mb-1">Tingkat (Opsional)</label>
                        <select name="tingkat" id="tingkat" class="input-field">
                            <option value="">Pilih Tingkat</option>
                            @foreach($tingkatOptions as $option)
                                <option value="{{ $option }}" {{ old('tingkat', $peserta->tingkat) == $option ? 'selected' : '' }}>{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="flex justify-end items-center mt-8 gap-4">
                    <a href="{{ route('peserta.index') }}"
                        class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-md font-semibold hover:bg-gray-300 transition-colors duration-200">Batal</a>
                    <button type="submit"
                        class="px-6 py-2.5 red-gradient text-white rounded-md font-semibold shadow-lg btn-hover">Simpan
                        Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
