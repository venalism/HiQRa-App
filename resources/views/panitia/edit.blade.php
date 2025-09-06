@extends('layouts.app')

@section('title', 'Edit Data Panitia')

@section('header')
    ✏️ Edit Data Panitia
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

            <form action="{{ route('panitia.update', $panitia->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div class="mb-4">
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                    <input type="text" name="nama" id="nama"
                        value="{{ old('nama', $panitia->nama) }}"
                        class="mt-1 form-input block w-full" required>
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email"
                        value="{{ old('email', $panitia->email) }}"
                        class="mt-1 form-input block w-full" required>
                </div>

                {{-- No HP --}}
                <div class="mb-4">
                    <label for="no_hp" class="block text-sm font-medium text-gray-700">No. HP</label>
                    <input type="text" name="no_hp" id="no_hp"
                        value="{{ old('no_hp', $panitia->no_hp) }}"
                        class="mt-1 form-input block w-full">
                </div>

                {{-- NPM --}}
                <div class="mb-4">
                    <label for="npm" class="block text-sm font-medium text-gray-700">NPM</label>
                    <input type="text" name="npm" id="npm"
                        value="{{ old('npm', $panitia->npm) }}"
                        class="mt-1 form-input block w-full" required>
                </div>

                {{-- Divisi --}}
                <div class="mb-4">
                    <label for="divisi_id" class="block text-sm font-medium text-gray-700">Divisi</label>
                    <select name="divisi_id" id="divisi_id" class="mt-1 form-select block w-full" required>
                        <option value="">-- Pilih Divisi --</option>
                        @foreach ($divisis as $divisi)
                            <option value="{{ $divisi->id }}" {{ $divisi->id == old('divisi_id', $panitia->divisi_id) ? 'selected' : '' }}>
                                {{ $divisi->jabatan->nama }} - {{ $divisi->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Password (Kosongkan jika tidak diubah)
                    </label>
                    <input type="password" name="password" id="password" class="mt-1 form-input block w-full">
                </div>

                {{-- Tombol --}}
                <div class="mt-6 flex justify-end">
                    <a href="{{ route('panitia.index') }}"
                        class="px-5 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md font-semibold shadow-md mr-2">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-semibold shadow-md">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection