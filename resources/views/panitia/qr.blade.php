@extends('layouts.app')

@section('title', 'QR Code panitia - ' . $panitia->nama)

@section('header')
    QR Code panitia
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow-md max-w-md mx-auto">
        <div class="p-8 text-center border-b">
            <h2 class="text-2xl font-bold text-gray-800">{{ $panitia->nama }}</h2>
            <p class="text-gray-600 mt-2">
                Silakan tunjukkan QR Code ini kepada penjaga untuk melakukan absensi.
            </p>
        </div>
        <div class="p-6 flex justify-center items-center bg-gray-50 rounded-b-lg">
            <div class="qr-code">
                {!! QrCode::size(300)->generate($panitia->barcode) !!}
            </div>
        </div>
        <div class="p-4 text-center">
            <a href="{{ route('panitia.qr.download', $panitia->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                Download QR Code
            </a>
        </div>
    </div>
@endsection