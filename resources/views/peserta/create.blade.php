@extends('layouts.app')

@section('title', 'Tambah peserta Baru')

@section('header')
    ➕ Tambah Peserta Baru
@endsection

@section('content')
    <div class="animate-fadeIn" x-data="{ openModal: false, modalType: '', prodiId: null, prodiNama: '' }">
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

            <form action="{{ route('peserta.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" id="nama" name="nama" class="mt-1 form-input block w-full" required>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" class="mt-1 form-input block w-full" required>
                </div>

                <div class="mb-4">
                    <label for="npm" class="block text-sm font-medium text-gray-700">NPM</label>
                    <input type="text" name="npm" id="npm" class="mt-1 block w-full" required>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" class="mt-1 block w-full" required>
                </div>

                <div class="mb-4">
                    <label for="no_hp" class="block text-sm font-medium text-gray-700">No. HP</label>
                    <input type="text" id="no_hp" name="no_hp" class="mt-1 form-input block w-full">
                </div>

                <div class="mb-4">
                    <label for="Kelas_id" class="block text-sm font-medium text-gray-700">Kelas</label>
                    <select id="kelas_id" name="kelas_id" class="mt-1 form-select block w-full">
                        <option value="">-- Pilih Kelas dan Prodi --</option>
                        @foreach ($kelas as $d)
                            <option value="{{ $d->id }}">{{ $d->prodi->nama }} - {{  $d->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-6 flex justify-end">
                    <a href="{{ route('peserta.index') }}"
                        class="px-5 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md font-semibold shadow-md mr-2">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-semibold shadow-md">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection