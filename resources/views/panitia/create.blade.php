@extends('layouts.app')

@section('title', 'Tambah Panitia Baru')

@section('header')
    ➕ Tambah Panitia Baru
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

        <form action="{{ route('panitia.store') }}" method="POST">
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
                <label for="no_hp" class="block text-sm font-medium text-gray-700">No. HP</label>
                <input type="text" id="no_hp" name="no_hp" class="mt-1 form-input block w-full">
            </div>

            <div class="mb-4">
                <label for="divisi_id" class="block text-sm font-medium text-gray-700">Divisi</label>
                <select id="divisi_id" name="divisi_id" class="mt-1 form-select block w-full">
                    <option value="">-- Pilih Divisi --</option>
                    @foreach ($divisi as $d)
                        <option value="{{ $d->id }}">{{ $d->jabatan->nama }} - {{  $d->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('panitia.index') }}"
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
