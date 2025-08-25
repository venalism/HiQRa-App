@extends('layouts.user_app')

@section('header')
    Pengaturan Akun
@endsection

@section('content')
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 bg-white border-b border-gray-200">

                    @if (session('status')) ... @endif
                    @if ($errors->any()) ... @endif

                    <form method="POST" action="{{ route('peserta.settings.update') }}">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label for="nama" class="block font-medium text-sm text-gray-700">Nama</label>
                            <input id="nama"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                type="text" name="nama" value="{{ old('nama', $user->nama) }}" required autofocus />
                        </div>

                        <div class="mt-4">
                            <label for="no_hp" class="block font-medium text-sm text-gray-700">No HP</label>
                            <input id="no_hp"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" required />
                        </div>

                        <div class="mt-4">
                            <label for="password" class="block font-medium text-sm text-gray-700">Password Baru (kosongkan
                                jika tidak ingin mengubah)</label>
                            <input id="password"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                type="password" name="password" />
                        </div>

                        <div class="mt-4">
                            <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Konfirmasi
                                Password Baru</label>
                            <input id="password_confirmation"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                type="password" name="password_confirmation" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection