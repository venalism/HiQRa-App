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
                        <label for="Kelas_id" class="block text-sm font-medium text-gray-700">Target Kelas dan Prodi</label>
                    <select id="kelas_id" name="kelas_id" class="mt-1 form-select block w-full">
                        <option value="">-- Pilih Kelas dan Prodi --</option>
                        @foreach ($kelas as $d)
                            <option value="{{ $d->id }}">{{ $d->prodi->nama }} - {{  $d->nama }}</option>
                        @endforeach
                    </select>
                    </div>
                    <!-- Target Panitia -->
                    <div class="md:col-span-2 border-t pt-4 mt-2">
                        <label for="target_type" class="block text-sm font-medium text-gray-700 mb-1">Target Panitia</label>
                        <select id="target_type" name="target_type" onchange="toggleTargetFields()" class="form-select block w-full">
                            <option value="">-- Pilih Jenis Target --</option>
                            <option value="panitia">Panitia Spesifik</option>
                            <option value="divisi">Divisi</option>
                        </select>
                    </div>

                    <div id="target_panitia" class="md:col-span-2 hidden">
                        <label for="panitia_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Panitia</label>
                        <select name="panitia_id[]" multiple class="form-select block w-full">
                            @foreach($panitias as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="target_divisi" class="md:col-span-2 hidden">
                        <label for="divisi_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Divisi</label>
                        <select name="divisi_id" class="form-select block w-full">
                            @foreach($divisis as $d)
                                <option value="{{ $d->id }}">{{ $d->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- <div id="target_jabatan" class="md:col-span-2 hidden">
                        <label for="jabatan_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Jabatan</label>
                        <select name="jabatan_id" class="form-select block w-full">
                            @foreach($jabatans as $j)
                                <option value="{{ $j->id }}">{{ $j->nama }}</option>
                            @endforeach
                        </select>
                    </div> --}}

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

<script>
    function toggleTargetFields() {
        const type = document.getElementById('target_type').value;
        document.getElementById('target_panitia').classList.add('hidden');
        document.getElementById('target_divisi').classList.add('hidden');

        if (type === 'panitia') {
            document.getElementById('target_panitia').classList.remove('hidden');
        } else if (type === 'divisi') {
            document.getElementById('target_divisi').classList.remove('hidden');
        } 
    }
</script>