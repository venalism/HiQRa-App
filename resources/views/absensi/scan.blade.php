@extends('layouts.app')

@section('title', 'Scan QR Code Absensi')

@section('header')
    📷 Scan QR Code Absensi
@endsection

<style>
    .success {
        color: green;
    }

    .error {
        color: red;
    }

    .warning {
        color: orange;
    }
</style>

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-center mb-4 pb-4 border-b">
            <h3 class="text-xl font-bold text-gray-800">Kegiatan Aktif:</h3>
            <p class="text-lg text-red-600 font-semibold">{{ $kegiatan->nama_kegiatan }}</p>
        </div>

        <div class="max-w-md mx-auto">
            <div id="reader" class="w-full border-4 border-gray-300 rounded-lg"></div>
            <div id="result" class="mt-4 p-4 rounded-lg text-center font-semibold text-lg min-h-[60px]"></div>
        </div>

        <div class="mt-6 p-4 bg-gray-50 border-l-4 border-red-500 rounded-r-lg">
            <h3 class="font-bold text-gray-700">📋 Petunjuk Penggunaan:</h3>
            <p class="text-gray-600 text-sm mt-1">
                Arahkan kamera ke QR code peserta untuk melakukan absensi. Pastikan QR code berada di dalam bingkai dan
                pencahayaan cukup.
            </p>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        const resultContainer = document.getElementById('result');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const kegiatanId = {{ $kegiatan->id }};

        function onScanSuccess(decodedText, decodedResult) {
            // Hentikan pemindaian setelah berhasil
            html5QrcodeScanner.pause();

            // Tampilkan pesan loading
            resultContainer.innerHTML = `<span class="status-indicator status-loading"></span>Memproses: ${decodedText}...`;
            resultContainer.className = 'loading';

            // Kirim data ke server
            fetch('{{ route('absensi.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        barcode: decodedText,
                        kegiatan_id: kegiatanId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.status === "sudah") {
                            resultContainer.innerHTML =
                                `<span class="status-indicator status-warning"></span>${data.message}`;
                            resultContainer.className = 'warning';
                            new Audio("{{ asset('sounds/warning.mp3') }}").play();
                        } else if (data.status === "baru") {
                            resultContainer.innerHTML =
                                `<span class="status-indicator status-success"></span>${data.message}`;
                            resultContainer.className = 'success';
                            new Audio("{{ asset('sounds/success2.mp3') }}").play();
                        }
                    } else {
                        resultContainer.innerHTML =
                            `<span class="status-indicator status-error"></span>${data.message || 'Terjadi kesalahan.'}`;
                        resultContainer.className = 'error';
                        new Audio("{{ asset('sounds/error.mp3') }}").play();
                    }
                })

                .catch(error => {
                    console.error('Error:', error);
                    resultContainer.innerHTML =
                        `<span class="status-indicator status-error"></span>Error: Tidak dapat terhubung ke server.`;
                    resultContainer.className = 'error';
                })
                .finally(() => {
                    // Lanjutkan pemindaian setelah 3 detik
                    setTimeout(() => {
                        resultContainer.innerHTML = '';
                        resultContainer.className = '';
                        html5QrcodeScanner.resume();
                    }, 3000);
                });
        }

        function onScanError(errorMessage) {
            // Abaikan error, pemindaian akan terus berjalan
        }

        const html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                },
                rememberLastUsedCamera: true
            },
            /* verbose= */
            false);

        html5QrcodeScanner.render(onScanSuccess, onScanError);
    </script>
@endpush
