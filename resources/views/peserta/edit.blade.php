@extends('layouts.app')

@section('title', 'Edit Peserta')

@section('header')
    ✏️ Edit Peserta
@endsection

@section('content')
    <div class="animate-fadeIn">
        <div class="bg-white rounded-lg shadow-md card-simple p-8">

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

            <form action="{{ route('peserta.update', ['pesertum' => $peserta->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama" id="nama"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                            value="{{ old('nama', $peserta->nama) }}" required>
                    </div>
                    <!-- Alamat Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                        <input type="email" name="email" id="email"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                            value="{{ old('email', $peserta->email) }}" required>
                    </div>
                    <!-- Nomor HP -->
                    <div>
                        <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                        <input type="text" name="no_hp" id="no_hp"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                            value="{{ old('no_hp', $peserta->no_hp) }}">
                    </div>
                    <!-- Jabatan -->
                    <div>
                        <label for="jabatan" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                        <select name="jabatan" id="jabatan"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                            <option value="" disabled>Pilih Kelas</option>
                            @foreach($kelasOptions as $kelas)
                                <option value="{{ $kelas }}" {{ old('jabatan', $peserta->jabatan) == $kelas ? 'selected' : '' }}>
                                    {{ $kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex justify-end items-center mt-8 gap-4">
                    <a href="{{ route('peserta.index') }}"
                        class="px-6 py-2 bg-gray-200 text-gray-800 rounded-md font-semibold hover:bg-gray-300 transition">Batal</a>
                    <button type="submit"
                        class="btn-hover px-6 py-2 red-gradient text-white rounded-md font-semibold shadow-lg">Update
                        Peserta</button>
                </div>
            </form>
        </div>
    </div>
@endsection