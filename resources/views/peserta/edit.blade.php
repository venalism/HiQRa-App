@extends('layouts.app')

@section('title', 'Edit Data Peserta')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header">
                <h4>Edit Data Peserta</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('peserta.update', $peserta->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nama --}}
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama', $peserta->nama) }}"
                            class="form-control" required>
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $peserta->email) }}"
                            class="form-control" required>
                    </div>

                    {{-- No HP --}}
                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No HP</label>
                        <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $peserta->no_hp) }}"
                            class="form-control" required>
                    </div>

                    {{-- NPM --}}
                    <div class="mb-4">
                        <label for="npm" class="block text-sm font-medium text-gray-700">NPM</label>
                        <input type="text" name="npm" id="npm" value="{{old('npm', $peserta->npm) }}" class="mt-1 block w-full"
                            required>
                    </div>

                    {{-- Kelas --}}
                    <div class="mb-3">
                        <label for="kelas_id" class="form-label">Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelas as $d)
                                <option value="{{ $d->id }}" {{ $d->id == old('kelas_id', $peserta->kelas_id) ? 'selected' : '' }}>
                                    {{ $d->prodi->nama }} - {{ $d->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tombol --}}
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('panitia.index') }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection